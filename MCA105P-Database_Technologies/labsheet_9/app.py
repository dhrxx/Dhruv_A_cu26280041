from flask import Flask, render_template, request, redirect, url_for, flash
from pymongo import MongoClient
from pymongo.errors import PyMongoError
from bson.objectid import ObjectId
import os

app = Flask(__name__)
app.secret_key = os.getenv("SECRET_KEY", "change-this-secret-key")
MONGO_URI = os.getenv("MONGO_URI", "mongodb://localhost:27017/")
client = MongoClient(MONGO_URI, serverSelectionTimeoutMS=3000)
db = client["CollegeDB"]
students = db["students"]

@app.route("/")
def home():
    try:
        client.admin.command("ping")
        connected = True
    except PyMongoError:
        connected = False
    return render_template("index.html", connected=connected,
                           total=students.count_documents({}) if connected else 0)

@app.route("/add", methods=["GET", "POST"])
def add_student():
    if request.method == "POST":
        try:
            doc = {
                "student_id": int(request.form["student_id"]),
                "name": request.form["name"].strip(),
                "age": int(request.form["age"]),
                "gender": request.form["gender"],
                "department": request.form["department"].strip(),
                "marks": float(request.form["marks"])
            }
            if not doc["name"] or not doc["department"] or not 0 <= doc["marks"] <= 100:
                raise ValueError("Enter valid name, department and marks (0–100).")
            if students.find_one({"student_id": doc["student_id"]}):
                raise ValueError("That Student ID already exists.")
            students.insert_one(doc)
            flash("Student added successfully.", "success")
            return redirect(url_for("view_students"))
        except (ValueError, KeyError) as exc:
            flash(str(exc), "error")
    return render_template("student_form.html", student=None, action="Add")

@app.route("/students")
def view_students():
    return render_template("students.html", students=list(students.find().sort("student_id", 1)))

@app.route("/search", methods=["GET", "POST"])
def search_student():
    result = None
    if request.method == "POST":
        try:
            result = students.find_one({"student_id": int(request.form["student_id"])})
            if result is None:
                flash("No student found with that ID.", "error")
        except ValueError:
            flash("Student ID must be a number.", "error")
    return render_template("search.html", student=result)

@app.route("/update/<student_id>", methods=["GET", "POST"])
def update_student(student_id):
    student = students.find_one({"_id": ObjectId(student_id)})
    if student is None:
        flash("Student not found.", "error")
        return redirect(url_for("view_students"))
    if request.method == "POST":
        try:
            updated = {
                "name": request.form["name"].strip(),
                "age": int(request.form["age"]),
                "department": request.form["department"].strip(),
                "marks": float(request.form["marks"])
            }
            if not updated["name"] or not updated["department"] or not 0 <= updated["marks"] <= 100:
                raise ValueError("Enter valid details and marks between 0 and 100.")
            students.update_one({"_id": student["_id"]}, {"$set": updated})
            flash("Student updated successfully.", "success")
            return redirect(url_for("view_students"))
        except ValueError as exc:
            flash(str(exc), "error")
    return render_template("student_form.html", student=student, action="Update")

@app.route("/delete/<student_id>", methods=["POST"])
def delete_student(student_id):
    students.delete_one({"_id": ObjectId(student_id)})
    flash("Student deleted.", "success")
    return redirect(url_for("view_students"))

@app.route("/filter")
def filter_students():
    department = request.args.get("department", "").strip()
    query = {}
    if department:
        query["department"] = {"$regex": f"^{department}$", "$options": "i"}
    if request.args.get("marks_gt") == "75":
        query["marks"] = {"$gt": 75}
    if request.args.get("marks_range") == "50-80":
        query["marks"] = {"$gte": 50, "$lte": 80}
    if request.args.get("age_gt") == "20":
        query["age"] = {"$gt": 20}
    results = list(students.find(query).sort("student_id", 1))
    return render_template("students.html", students=results, filtered=True)

if __name__ == "__main__":
    app.run(debug=True)
