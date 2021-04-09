#!/usr/bin/env python

import numpy as np
import matplotlib.pyplot as plt
import pandas as pd
import numpy as np
import sklearn
from sklearn import preprocessing
from sklearn.model_selection import train_test_split
from sklearn.neighbors import KNeighborsClassifier
from sklearn import metrics
import csv
import sys
import time

# retrieve command line arguments
if len(sys.argv) == 7:
    age = sys.argv[1]
    income = sys.argv[2]
    emp_length = sys.argv[3]
    loan_amount = sys.argv[4]
    loan_interest = sys.argv[5]
    cred_hist_length = sys.argv[6]
    loan_percent_income = int(loan_amount)/int(income)
else:
    age = 30
    income = 70000
    emp_length = 2
    loan_amount = 3000
    loan_interest = 15
    loan_percent_income = 5
    cred_hist_length = 9
    loan_percent_income = int(loan_amount)/int(income)

# store information from csv file in pandas dataframe and remove rows containing null values
# learned how to do this here: https://www.youtube.com/watch?v=mqI7xtlE2VU
df = pd.read_csv('loan_data.csv')
df.dropna(inplace=True)


# gather independent variables from data and normalize the data
X = df[['person_age', 'person_income', 'person_emp_length', 'loan_amnt', 'loan_int_rate', 'loan_percent_income', 'cb_person_cred_hist_length']] .values  
X = preprocessing.StandardScaler().fit(X).transform(X.astype(float))

# now get the dependent variables from set (loan_status)
y = df['loan_status'].values

# establish the testing and training sets for the model
# here we are making the training set 80% of the data
X_train, X_test, y_train, y_test = train_test_split( X, y, test_size=0.2, random_state=4)

# now, we actually create the model!

# set k (I have done some testing and found this k value was the best for the model)
k = 7
neigh = KNeighborsClassifier(n_neighbors = k).fit(X_train,y_train)

yhat = neigh.predict(X_test)

# finally, predict the default value using the model

# to do this, we must normalize the given independent variable to fit the data
X1 = df[['person_age', 'person_income', 'person_emp_length', 'loan_amnt', 'loan_int_rate', 'loan_percent_income', 'cb_person_cred_hist_length']] .values  
X1[-1] = ([age, income, emp_length, loan_amount, loan_interest, loan_percent_income, cred_hist_length])
X1 = preprocessing.StandardScaler().fit(X1).transform(X1.astype(float))

prediction = neigh.predict(X1[-1:])[0]

# write this prediction to the user_info.csv file
with open('user_info.csv', mode='w') as csvfile:
    csvwriter = csv.writer(csvfile)
    csvwriter.writerow(str(prediction))

time.sleep(0.1)

print(prediction)



















