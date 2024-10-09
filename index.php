<?php
// Conversion factors to meters
$conversionFactorsToMeters = array(
    'millimeter' => 0.001,
    'centimeter' => 0.01,
    'decimeter' => 0.1,
    'meter' => 1.0,
    'kilometer' => 1000.0,
    'inch' => 0.0254,
    'foot' => 0.3048,
    'yard' => 0.9144,
    'mile' => 1609.34,
    'nautical mile' => 1852.0
);

// Length conversion function
function convertLength($inputValue, $fromUnit, $toUnit, $conversionFactorsToMeters) {
    // Convert from the original unit to meters
    $valueInMeters = $inputValue * $conversionFactorsToMeters[strtolower($fromUnit)];
    
    // Convert from meters to the desired unit
    $conversionFactorsFromMeters = array_map(function($factor) {
        return 1 / $factor;
    }, $conversionFactorsToMeters);
    
    $convertedValue = $valueInMeters * $conversionFactorsFromMeters[strtolower($toUnit)];
    
    return $convertedValue;
}

// Initialize error message and output message
$errorMessage1 = '';
$errorMessage2 = '';
$outputMessage1 = '';
$outputMessage2 = '';

// First container (allows letters, symbols, and numbers)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['convert_with_symbols'])) {
    $inputValue = $_POST['value_with_symbols'];
    $fromUnit = $_POST['from_unit_with_symbols'];
    $toUnit = $_POST['to_unit_with_symbols'];

    // Sanitize input: remove all non-numeric characters except for the decimal point
    $sanitizedValue = preg_replace("/[^0-9.]/", '', $inputValue);
    
    if (empty($sanitizedValue) || !is_numeric($sanitizedValue)) {
        $errorMessage1 = "Error: Please enter a valid numeric value.";
    } else {
        $sanitizedValue = floatval($sanitizedValue);
        $convertedValue = convertLength($sanitizedValue, $fromUnit, $toUnit, $conversionFactorsToMeters);
        $outputMessage1 = "<p>$inputValue $fromUnit is equal to " . round($convertedValue, 4) . " $toUnit</p>";
    }
}

// Second container (strict numeric input with decimal validation)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['convert_numeric_only'])) {
    $inputValue = $_POST['value_numeric_only'];
    $fromUnit = $_POST['from_unit_numeric_only'];
    $toUnit = $_POST['to_unit_numeric_only'];

    // Validate numeric input
    if (!is_numeric($inputValue)) {
        $errorMessage2 = "Error: Only numeric values are allowed.";
    } else {
        $inputValue = floatval($inputValue);
        $convertedValue = convertLength($inputValue, $fromUnit, $toUnit, $conversionFactorsToMeters);
        $outputMessage2 = "<p>$inputValue $fromUnit is equal to " . round($convertedValue, 4) . " $toUnit</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Length Measurement Converter</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container-wrapper {
            display: flex;
            flex-direction: column; /* Change to column to stack containers */
            gap: 20px; /* Add gap between containers */
        }
        .converter-container {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 350px;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        label {
            font-weight: bold;
            margin-bottom: 5px;
            display: block;
        }
        input[type="text"],
        select {
            width: 100%;
            padding: 8px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
        input[type="number"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #218838;
        }
        p {
            font-size: 18px;
            text-align: center;
            color: #333;
        }
        .error {
            color: red;
            text-align: center;
            font-size: 16px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container-wrapper">
        <!-- First Container (Allows letters and symbols) -->
        <div class="converter-container">
            <h1>Length Converter (With Symbols)</h1>
            <form method="post">
                <label for="value_with_symbols">Enter value (letters/symbols allowed):</label>
                <input type="text" id="value_with_symbols" name="value_with_symbols">
                
                <label for="from_unit_with_symbols">Convert from:</label>
                <select id="from_unit_with_symbols" name="from_unit_with_symbols">
                    <option value="millimeter">Millimeter</option>
                    <option value="centimeter">Centimeter</option>
                    <option value="decimeter">Decimeter</option>
                    <option value="meter">Meter</option>
                    <option value="kilometer">Kilometer</option>
                    <option value="inch">Inch</option>
                    <option value="foot">Foot</option>
                    <option value="yard">Yard</option>
                    <option value="mile">Mile</option>
                    <option value="nautical mile">Nautical Mile</option>
                </select>
                
                <label for="to_unit_with_symbols">Convert to:</label>
                <select id="to_unit_with_symbols" name="to_unit_with_symbols">
                    <option value="millimeter">Millimeter</option>
                    <option value="centimeter">Centimeter</option>
                    <option value="decimeter">Decimeter</option>
                    <option value="meter">Meter</option>
                    <option value="kilometer">Kilometer</option>
                    <option value="inch">Inch</option>
                    <option value="foot">Foot</option>
                    <option value="yard">Yard</option>
                    <option value="mile">Mile</option>
                    <option value="nautical mile">Nautical Mile</option>
                </select>
                
                <input type="submit" name="convert_with_symbols" value="Convert">
            </form>

            <?php
            if (!empty($errorMessage1)) {
                echo "<div class='error'>$errorMessage1</div>";
            }

            if (!empty($outputMessage1)) {
                echo $outputMessage1;
            }
            ?>
        </div>

        <!-- Second Container (Strict numeric input with decimal validation) -->
        <div class="converter-container">
            <h1>Length Converter (Numbers Only)</h1>
            <form method="post">
                <label for="value_numeric_only">Enter numeric value:</label>
                <input type="number" id="value_numeric_only" name="value_numeric_only" step="0.01" required>
                
                <label for="from_unit_numeric_only">Convert from:</label>
                <select id="from_unit_numeric_only" name="from_unit_numeric_only">
                    <option value="millimeter">Millimeter</option>
                    <option value="centimeter">Centimeter</option>
                    <option value="decimeter">Decimeter</option>
                    <option value="meter">Meter</option>
                    <option value="kilometer">Kilometer</option>
                    <option value="inch">Inch</option>
                    <option value="foot">Foot</option>
                    <option value="yard">Yard</option>
                    <option value="mile">Mile</option>
                    <option value="nautical mile">Nautical Mile</option>
                </select>
                
                <label for="to_unit_numeric_only">Convert to:</label>
                <select id="to_unit_numeric_only" name="to_unit_numeric_only">
                    <option value="millimeter">Millimeter</option>
                    <option value="centimeter">Centimeter</option>
                    <option value="decimeter">Decimeter</option>
                    <option value="meter">Meter</option>
                    <option value="kilometer">Kilometer</option>
                    <option value="inch">Inch</option>
                    <option value="foot">Foot</option>
                    <option value="yard">Yard</option>
                    <option value="mile">Mile</option>
                    <option value="nautical mile">Nautical Mile</option>
                </select>
                
                <input type="submit" name="convert_numeric_only" value="Convert">
            </form>

            <?php
            if (!empty($errorMessage2)) {
                echo "<div class='error'>$errorMessage2</div>";
            }

            if (!empty($outputMessage2)) {
                echo $outputMessage2;
            }
            ?>
        </div>
    </div>
</body>
</html>
