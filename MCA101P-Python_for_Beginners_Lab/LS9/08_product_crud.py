# Store and manage product details using CRUD operations.
products = {}
while True:
    print("\\n1.Add 2.View 3.Update price 4.Delete 5.Exit")
    choice = input("Choose: ")
    if choice == "1":
        product_id = input("Product ID: ")
        products[product_id] = {"name": input("Product name: "),
                                "price": float(input("Price: ")),
                                "quantity": int(input("Quantity: "))}
        print("Product saved.")
    elif choice == "2":
        for key, value in products.items(): print(key, value)
        if not products: print("No products.")
    elif choice == "3":
        product_id = input("Product ID: ")
        if product_id in products:
            products[product_id]["price"] = float(input("New price: "))
            print("Price updated.")
        else: print("Product not found.")
    elif choice == "4":
        product_id = input("Product ID: ")
        print("Deleted." if products.pop(product_id, None) is not None else "Not found.")
    elif choice == "5": break
    else: print("Invalid choice.")
