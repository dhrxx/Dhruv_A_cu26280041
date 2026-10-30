from flask import Flask, render_template, request, redirect, url_for, flash
from pymongo import MongoClient
from pymongo.errors import PyMongoError
from bson.objectid import ObjectId
import os

app = Flask(__name__)
app.secret_key = os.getenv("SECRET_KEY", "change-this-secret-key")
client = MongoClient(os.getenv("MONGO_URI", "mongodb://localhost:27017/"),
                     serverSelectionTimeoutMS=3000)
db = client["HospitalDB"]
medicines = db["medicines"]

def medicine_form():
    doc = {
        "medicine_id": int(request.form["medicine_id"]),
        "name": request.form["name"].strip(),
        "category": request.form["category"].strip(),
        "price": float(request.form["price"]),
        "stock": int(request.form["stock"]),
        "manufacturer": request.form["manufacturer"].strip()
    }
    if not doc["name"] or not doc["category"] or not doc["manufacturer"]:
        raise ValueError("All medicine fields are required.")
    if doc["price"] < 0 or doc["stock"] < 0:
        raise ValueError("Price and stock cannot be negative.")
    return doc

@app.route("/")
def home():
    try:
        client.admin.command("ping")
        connected = True
        total = medicines.count_documents({})
    except PyMongoError:
        connected, total = False, 0
    return render_template("index.html", connected=connected, total=total)

@app.route("/add", methods=["GET", "POST"])
def add_medicine():
    if request.method == "POST":
        try:
            doc = medicine_form()
            if medicines.find_one({"medicine_id": doc["medicine_id"]}):
                raise ValueError("That Medicine ID already exists.")
            medicines.insert_one(doc)
            flash("Medicine added successfully.", "success")
            return redirect(url_for("view_medicines"))
        except (ValueError, KeyError) as exc:
            flash(str(exc), "error")
    return render_template("medicine_form.html", medicine=None, action="Add")

@app.route("/medicines")
def view_medicines():
    items = list(medicines.find().sort("medicine_id", 1))
    return render_template("medicines.html", medicines=items)

@app.route("/search", methods=["GET", "POST"])
def search_medicine():
    result = None
    if request.method == "POST":
        term = request.form.get("term", "").strip()
        try:
            result = medicines.find_one({"medicine_id": int(term)})
        except ValueError:
            result = medicines.find_one({"name": {"$regex": term, "$options": "i"}}) if term else None
        if result is None:
            flash("No matching medicine found.", "error")
    return render_template("search.html", medicine=result)

@app.route("/update-stock", methods=["GET", "POST"])
def update_stock():
    if request.method == "POST":
        try:
            medicine_id = int(request.form["medicine_id"])
            new_stock = int(request.form["stock"])
            if new_stock < 0:
                raise ValueError("Stock cannot be negative.")
            result = medicines.update_one({"medicine_id": medicine_id},
                                          {"$set": {"stock": new_stock}})
            if result.matched_count:
                flash("Stock updated successfully.", "success")
            else:
                flash("Medicine ID not found.", "error")
            return redirect(url_for("view_medicines"))
        except ValueError as exc:
            flash(str(exc), "error")
    return render_template("update_stock.html")

@app.route("/delete/<medicine_id>", methods=["POST"])
def delete_medicine(medicine_id):
    medicines.delete_one({"_id": ObjectId(medicine_id)})
    flash("Medicine deleted.", "success")
    return redirect(url_for("view_medicines"))

@app.route("/low-stock")
def low_stock():
    items = list(medicines.find({"stock": {"$lt": 30}}).sort("stock", 1))
    return render_template("medicines.html", medicines=items, heading="Low Stock Medicines")

@app.route("/report")
def report():
    total_medicines = medicines.count_documents({})
    totals = list(medicines.aggregate([{"$group": {"_id": None, "total": {"$sum": "$stock"}}}]))
    total_stock = totals[0]["total"] if totals else 0
    most_expensive = medicines.find_one(sort=[("price", -1)])
    cheapest = medicines.find_one(sort=[("price", 1)])
    low_count = medicines.count_documents({"stock": {"$lt": 30}})
    return render_template("report.html", total_medicines=total_medicines,
                           total_stock=total_stock, low_count=low_count,
                           most_expensive=most_expensive, cheapest=cheapest)

@app.route("/queries")
def query_practice():
    category = request.args.get("category", "").strip()
    manufacturer = request.args.get("manufacturer", "").strip()
    query = {}
    if category: query["category"] = {"$regex": f"^{category}$", "$options": "i"}
    if request.args.get("price_gt") == "50": query["price"] = {"$gt": 50}
    if request.args.get("price_lt") == "100": query["price"] = {"$lt": 100}
    if request.args.get("stock_lt") == "30": query["stock"] = {"$lt": 30}
    if manufacturer: query["manufacturer"] = {"$regex": f"^{manufacturer}$", "$options": "i"}
    sort_field = request.args.get("sort", "")
    cursor = medicines.find(query)
    if sort_field in ("price", "stock"): cursor = cursor.sort(sort_field, 1)
    return render_template("medicines.html", medicines=list(cursor), heading="Query Results")

@app.route("/supplier-demo")
def supplier_demo():
    # Q11: three sample documents with embedded supplier subdocuments.
    sample = [
      {"medicine_id": 901, "name": "Sample Medicine A", "category": "General",
       "price": 45, "stock": 40, "manufacturer": "Demo Pharma",
       "supplier": {"name": "North Distributors", "phone": "9000000001", "city": "Roorkee"}},
      {"medicine_id": 902, "name": "Sample Medicine B", "category": "General",
       "price": 75, "stock": 25, "manufacturer": "Demo Pharma",
       "supplier": {"name": "City Med Supply", "phone": "9000000002", "city": "Haridwar"}},
      {"medicine_id": 903, "name": "Sample Medicine C", "category": "Pain Relief",
       "price": 30, "stock": 80, "manufacturer": "Demo Pharma",
       "supplier": {"name": "Health Wholesale", "phone": "9000000003", "city": "Saharanpur"}}
    ]
    for item in sample:
        medicines.update_one({"medicine_id": item["medicine_id"]}, {"$set": item}, upsert=True)
    items = list(medicines.find({"supplier": {"$exists": True}}))
    return render_template("medicines.html", medicines=items, heading="Medicines with Supplier Details")

if __name__ == "__main__":
    app.run(debug=True)
