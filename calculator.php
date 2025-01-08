<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Professional Calculator</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Roboto', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: linear-gradient(135deg, #28313B, #485461);
            color: white;
        }

        .calculator {
            width: 350px;
            background: #1F1F1F;
            border-radius: 20px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.5);
            padding: 20px;
        }

        .display {
            width: 100%;
            height: 70px;
            background: #2A2A2A;
            border: none;
            border-radius: 10px;
            font-size: 2rem;
            color: #FFFFFF;
            text-align: right;
            padding: 10px;
            margin-bottom: 20px;
        }

        .buttons {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
        }

        button {
            height: 60px;
            background: #3E3E3E;
            border: none;
            border-radius: 10px;
            font-size: 1.5rem;
            color: #FFFFFF;
            cursor: pointer;
            transition: transform 0.2s, background 0.3s;
        }

        button:hover {
            background: #575757;
            transform: scale(1.05);
        }

        .operator {
            background: #007BFF;
        }

        .operator:hover {
            background: #0056B3;
        }

        .clear {
            background: #FF414D;
        }

        .clear:hover {
            background: #E0313A;
        }

        .equals {
            background: #FFC107;
            grid-column: span 2;
        }

        .equals:hover {
            background: #E0A800;
        }
    </style>
</head>
<body>
    <div class="calculator">
        <input type="text" id="display" class="display" disabled />
        <div class="buttons">
            <button onclick="appendNumber('7')">7</button>
            <button onclick="appendNumber('8')">8</button>
            <button onclick="appendNumber('9')">9</button>
            <button class="operator" onclick="setOperator('/')">&divide;</button>

            <button onclick="appendNumber('4')">4</button>
            <button onclick="appendNumber('5')">5</button>
            <button onclick="appendNumber('6')">6</button>
            <button class="operator" onclick="setOperator('*')">&times;</button>

            <button onclick="appendNumber('1')">1</button>
            <button onclick="appendNumber('2')">2</button>
            <button onclick="appendNumber('3')">3</button>
            <button class="operator" onclick="setOperator('-')">&minus;</button>

            <button onclick="appendNumber('0')">0</button>
            <button onclick="appendNumber('.')">.</button>
            <button class="equals" onclick="calculateResult()">=</button>
            <button class="operator" onclick="setOperator('+')">+</button>

            <button class="clear" onclick="clearDisplay()">C</button>
        </div>
    </div>

    <script>
        let currentInput = '';
        let previousInput = '';
        let operator = null;

        function appendNumber(number) {
            if (currentInput.length >= 12) return; // Limit input length for readability
            currentInput += number;
            updateDisplay();
        }

        function setOperator(op) {
            if (currentInput === '') return;
            if (previousInput !== '') {
                calculateResult();
            }
            operator = op;
            previousInput = currentInput;
            currentInput = '';
        }

        function calculateResult() {
            if (operator === null || currentInput === '' || previousInput === '') return;
            const prev = parseFloat(previousInput);
            const current = parseFloat(currentInput);
            let result;
            switch (operator) {
                case '+':
                    result = prev + current;
                    break;
                case '-':
                    result = prev - current;
                    break;
                case '*':
                    result = prev * current;
                    break;
                case '/':
                    result = current === 0 ? 'Error' : prev / current;
                    break;
                default:
                    return;
            }
            currentInput = result.toString();
            operator = null;
            previousInput = '';
            updateDisplay();
        }

        function clearDisplay() {
            currentInput = '';
            previousInput = '';
            operator = null;
            updateDisplay();
        }

        function updateDisplay() {
            document.getElementById('display').value = currentInput;
        }
    </script>
</body>
</html>
