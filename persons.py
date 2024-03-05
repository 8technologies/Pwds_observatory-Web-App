import mysql.connector

file_path = "persons.sql"  # Replace with the actual file path


# MySQL connection configuration
mysql_config = {
    'user': 'root',
    'password': 'toor',
    'host': 'localhost',
    'database': 'demo2_ict4pwd',
     'auth_plugin': 'mysql_native_password'
}

# Establish MySQL connection
connection = mysql.connector.connect(**mysql_config)
cursor = connection.cursor()

# Open the file in read mode
with open(file_path, 'r') as file:
    for line in file:
        if line.startswith('(') and line.endswith('),\n'):
            elements = line.strip()[1:-2].split(', ')
            indices_to_insert = [0, 5, 9, 10, 11, 16, 15]  # Indices of id, created_at, updated_at, name, phone_number, email, district_id
            filtered_elements = [elements[i][1:-1] if elements[i].startswith("'") and elements[i].endswith("'") else elements[i] for i in indices_to_insert]
            print(filtered_elements)
            
            # Construct the SQL INSERT statement
            insert_query = "INSERT INTO people (id, created_at, updated_at, name, phone_number, email, district_of_origin, sex, dob) VALUES (%s,NOW(),NOW(), %s,%s, %s, %s, %s, %s)"
            try:
                # Execute the INSERT statement
                cursor.execute(insert_query, filtered_elements)
            except mysql.connector.Error as error:
                print("Failed to insert record into MySQL table {}".format(error))

# Commit the changes and close the connection
connection.commit()
connection.close()
