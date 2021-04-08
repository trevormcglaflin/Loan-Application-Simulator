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
    
        $command  = escapeshellcmd('python3 classify.py ' . $age . " " . $income . " " . $empLength . " " . $amount . " " . $interest . " " . $yearsOfCredit);
        
        $output = $shell_exec($command);
        echo $output;
        
        print '<p>' . $command . '</p>';

    }
}
?>
    <body>
        <header>
            <h1>McGlaflin & McGlaflin & Co</hl>
        </header>
        <div>
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
        <footer>
        <p>McGlaflin & McGlaflin & Co</p>
        <p>Email: trevor.mcglafin@uvm.edu</p>
        <p>Phone: (802)324-7275</p>
        </footer>
    </body> 
</body>
</html>