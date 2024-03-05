import pandas as pd
import mysql.connector

# Connect to the MySQL database
cnx = mysql.connector.connect(
    host='localhost',
    user='root',
    password='toor',
    database='demo2_ict4pwd',
    auth_plugin = 'mysql_native_password'

)

# Create a cursor object to execute SQL queries
cursor = cnx.cursor()

# Read the Excel file
df = pd.read_excel('Data_for_service_providers.xlsx')

# Iterate over each row in the DataFrame
for index, row in df.fillna('NULL').iterrows():
    # Extract the values from each column
    name = row[0]
    physical_address = row[1]
    postal_address = row[2]
    email = row[3]
    telephone = row[4]
    mission = row[5]
    target_group = row[6]
    disability_category = row[7]
    level_of_operation = row[8]
    districts_of_operation = row[9]
    services_offered = row[10]
    affiliated_organizations = row[11]

    print(name, physical_address, postal_address, email, telephone, mission, target_group, disability_category, level_of_operation, districts_of_operation, services_offered, affiliated_organizations)
    # Prepare the SQL query to insert the data into the table
    sql = "INSERT INTO service_providers (name, physical_address, postal_address, email, telephone, mission, target_group, disability_category, level_of_operation, districts_of_operation, services_offered, affiliated_organizations, created_at, updated_at) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, NOW(), NOW())"
    values = (name, physical_address, postal_address, email, telephone, mission, target_group, disability_category, level_of_operation, districts_of_operation, services_offered, affiliated_organizations)

    # Execute the SQL query
    cursor.execute(sql, values)

# Commit the changes to the database
cnx.commit()

# Close the cursor and database connection
cursor.close()
cnx.close()
