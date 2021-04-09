<!DOCTYPE HTML>
<html lang = "en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="author" content="Trevor McGlaflin">
        <meta name="description" content="Loan Application">
        <title>Loan Application</title>

        <link rel = "stylesheet"
            href = "custom.css?version=<?php print time(); ?>"
            type="text/css">
    </head>

    <body>
        <header>
            <h1>McGlaflin & McGlaflin & Co</hl>
        </header>
        <main class = "grid-container">
        <?php
include 'constants.php';
$isValidData = true;

function getData($field) {
    if (!isset($_POST[$field])) {
       $data = "";
    }
    else {
       $data = trim($_POST[$field]);
       $data = htmlspecialchars($data);
    }
    return $data;
}

if(isset($_POST['btnSubmit'])) {
    // sanitize
    $age = (int) getData('age');
    $income = (int) getData('income');
    $empLength = (int) getData('emp-length');
    $yearsOfCredit = (int) getData('years-credit');
    $amount = (int) getData('amount');
    $interest = getData('interest');

    // validate
    if ($age < 18 or $age > 110) {
        print '<p class="mistake">Age must be an integer between 18 and 110.</p>';
        $isValidData = false;
    }
    if ($income < 0) {
        print '<p class="mistake">Income can not be negative.</p>';
        $isValidData = false;
    }
    if ($empLength > $age or $empLength < 0) {
        print '<p class="mistake">Empoyment length cannot be negative or greater than age.</p>';
        $isValidData = false;
    }
    if ($yearsOfCredit > $age or $yearsOfCredit < 0) {
        print '<p class="mistake">Years of credit cannot be negative or greater than age.</p>';
        $isValidData = false;
    }
    if ($interest < 0 or $interest > 100) {
        print '<p class="mistake">Interest cannot be negative or greater than 100%.</p>';
        $isValidData = false;
    }

    # if the data is valid, call the python script to run the ML classification algorithm
    if ($isValidData) {
        $create_user_info = shell_exec('touch user_info.csv');
        $output = shell_exec('python classify.py ' . (string) $age . " " . (string) $income . " " . (string) $empLength . " " . (string) $amount . " " . (string) $interest . " " . (string) $yearsOfCredit);
        $prediction = shell_exec('cat user_info.csv');
        $clear_user_info = shell_exec('rm user_info.csv');

        print $prediction;

        if ($prediction == 1) {
            print '<div class="grid-item2">';
            print '<p id="rejected" class="grid-item2">' . "Application Rejected!!" . '</p>';
            print '<figure><img src="rejected.gif" alt="rejected"></figure>';
            print '</div>';
        }

        else {
            print '<div class = "grid-item2">';
            print '<p id = "accepted">' . "Application Accepted!!" . '</p>';
            print '<figure><img src="approved.gif" alt="rejected"></figure>';
            print '</div>';
            $output2 = shell_exec('g++ -std=c++1y get_payment_data.cpp');
            $output3 = shell_exec('./a.out ' . (string) $amount . ' 0 ' . (string) $interest*100 .  ' 12');
            
            $output4 = shell_exec("cat payment_schedule.csv");
            
            print '<table class="grid-item3">';
            print '<tbody>';
            $rows = explode("\n", $output4);
            foreach ($rows as $row) {
                print '<tr>';
                $columns = explode(",", $row);
                foreach ($columns as $column) {
                    print '<td>' . $column . '</td>';
                }
                print '</tr>';
            }

            print '</tbody>';
            print '</table>';
        }
    }
}
?>
        <div class="grid-item1">
            <h3>Personal Loan Application</h3>
            <form action="<?php print $_SERVER['PHP_SELF']; ?>" id="loanForm" method="post">
                <p>
                    <label for="age">Age</label>
                    <input type="number" name="age" id="age" min="18" max="110">
                </p>
                <p>
                    <label for="income">Annual Income $</label>
                    <input type="number" name="income" id="income" min="10000" max="1000000000" step="1000">
                </p>
                <p>
                    <label for="emp-length">Years Employed</label>
                    <input type="number" name="emp-length" id="emp-length" min="0" max="110">
                </p>
                <p>
                    <label for="years-credit">Years of Credit History</label>
                    <input type="number" name="years-credit" id="years-credit" min="0" max="110">
                </p>
                <p>
                    <label for="amount">Loan Amount Desired</label>
                    <input type="number" name="amount" id="amount" min="100" max="10000000" step="500">
                </p>
                <p>
                    <label for="interest">Interest Rate Desired (%)</label>
                    <input type="number" name="interest" id="interest" min="0" max="100" step="0.25">
                </p>
                <p>
                    <input type="submit" value="Apply" tabindex="999" name="btnSubmit">
                </p>
            </form>
        </div>
        </main>
        <footer>
        <p>McGlaflin & McGlaflin & Co</p>
        <p>Email: trevor.mcglafin@uvm.edu</p>
        <p>Phone: (802)324-7275</p>
        </footer>
    </body> 
</body>
</html>