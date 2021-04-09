#include <ctime>
#include <fstream>
#include <iostream>
#include <string>
#include <vector>
#include <math.h>
using namespace std;


int main(int argc, char* argv[]) {

    double amount;
    double down;
    double interest;
    int periods; 
    
    // Get command line input
    if (argc > 1) {
        amount = stoi(argv[1]);
        down = stoi(argv[2]);
        interest = stoi(argv[3]);
        periods = stoi(argv[4]);
    } else {
        amount = 10000;
        down = 0;
        interest = 0.1;
        periods = 60;
    }

    // initialize empty array of correct shape
    double payment_array[periods][5];

    // calculate monthly payment
    double loan_amount = amount - down;
    double monthly_interest = interest/1200;
    double monthly_payment = loan_amount / ((1 - pow((1 + monthly_interest), -(periods)))/monthly_interest);

    // now, populate the payment_array
    payment_array[0][0] = 1;
    payment_array[0][1] = monthly_payment;
    payment_array[0][2] = loan_amount * monthly_interest;
    payment_array[0][3] = monthly_payment - payment_array[0][2];
    payment_array[0][4] = loan_amount - payment_array[0][3];

    for (int row = 1; row < periods; row++) {
        payment_array[row][0] = row + 1;
        payment_array[row][1] = monthly_payment;
        payment_array[row][2] = payment_array[row-1][4] * monthly_interest;
        payment_array[row][3] = monthly_payment - payment_array[row][2];
        payment_array[row][4] = payment_array[row-1][4] - payment_array[row][3];
    }

    // now load csv file with array content, this will be read by the python file
    ofstream fOut;
    fOut.open("payment_schedule.csv");
    fOut << "Period,MonthlyPayment,Interest,Principal,Balance" << endl;

    for (int k = 0; k < periods; k++) {
        for (int h = 0; h < 5; h++) {
            if (h != 4) {
                fOut << round(payment_array[k][h]) << ",";
            }
            else {
                fOut << round(payment_array[k][h]) << endl;
            }
        }
    }
    fOut.close();

    return 0;
}