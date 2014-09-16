#Script 2
#Your name:Jimmy 


#Select the customername of customers from the country 'USA'
#10 pts

SELECT customername FROM customers WHERE country = 'USA';

#Select the product code and total number of that product code ordered
#from the orderdetails table. Return only the rows that have more than
#1000 ordered. You will need to learn about the HAVING keyword(Google MySQL HAVING)
#it is very similar to the WHERE keyword but works with aggregate functions
#10 pts

SELECT productCode, SUM(quantityOrdered) AS totalOrdered
FROM orderdetails 
GROUP BY productCode
HAVING totalOrdered > 1000;

#Select the firstname, lastname and email of employees who are in offices located in
#the state of 'NY'
#10 pts

SELECT firstname, lastname, email 
FROM employees 
WHERE officeCode = (SELECT officeCode 
			FROM offices 
			WHERE state ='NY');

#Select the customerName of customers who ordered at least 10 items in the
#'planes' productLine. Warning: this is probably the hardest query here and you will need
#to use the HAVING keyword
#20 pts

SELECT customerName, SUM(quantityOrdered) AS planesOrderd
FROM customers, orders, orderdetails, products
WHERE customers.customerNumber = orders.customerNumber 
AND orders.orderNumber = orderdetails.orderNumber
AND orderdetails.productCode = products.productCode
AND products.productLine = 'Planes'
GROUP BY customerName
HAVING planesOrderd > 10