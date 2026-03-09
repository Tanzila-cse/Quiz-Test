<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit();
}

// Database connection
$conn = new mysqli("localhost", "root", "", "quiz_website");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch logged-in user ID
$username = $_SESSION['username'];
$result = $conn->query("SELECT id FROM users WHERE username='$username'");
$user = $result->fetch_assoc();
$user_id = $user['id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Quiz App</title>
  <link rel="stylesheet" href="quiz.css" />
</head>
<body>
  <div class="container" id="quiz-container">
    <!-- Quiz dynamically rendered here -->
  </div>

  <script>
    const userId = <?= $user_id ?>;
    const questions = [
      {
        numb: 1,
        question: "What does HTML stand for?",
        answer: "Hyper Text Markup Language",
        options: [
          "Hyper Text Multi Language",
          "Hyper Text Markup Language",
          "Hyper Text Multiple Language",
          "Home Text Multi Language"
        ],
      },
      {
        numb: 2,
        question: "What does CSS stand for?",
        answer: "Cascading Style Sheet",
        options: [
          "Cascading Style Sheet",
          "Cute Style Sheet",
          "Computer Style Sheet",
          "Code Style Sheet"
        ],
      },
      {
        numb: 3,
        question: "What does PHP stand for?",
        answer: "Hypertext Preprocessor",
        options: [
          "Hypertext Preprocessor",
          "Hometext Programming",
          "Hypertext Programming",
          "Programming Hypertext Preprocessor"
        ],
      },
      {
      numb: 4,
      question: "What does SQL stand for?",
      answer: "D.Structured Query Language",
      options: [
        "A.Strength Query Language",
        "B.Stylesheet Query Language",
        "C.Science Question Language",
        "D.Structured Query Language",
      ],
    },
    {
      numb: 5,
      question: "What does XML stand for?",
      answer: "D.Extensible Markup Language",
      options: [
        "A.Exccellent Muiltiple Language",
        "B.Explore Muiltiple Language",
        "C.Extra Markup Language",
        "D.Extensible Markup Language",
      ],
    }
    ];

    let currentQuestionIndex = 0;
    let correctAnswers = 0;

    const quizContainer = document.getElementById("quiz-container");

    function renderQuestion() {
      if (currentQuestionIndex < questions.length) {
        const question = questions[currentQuestionIndex];
        quizContainer.innerHTML = `
          <div class="question">
            <h2>Q${question.numb}: ${question.question}</h2>
            ${question.options
              .map(
                (opt, i) =>
                  `<div><input type="radio" name="answer" id="option${i}" value="${opt}"><label for="option${i}">${opt}</label></div>`
              )
              .join("")}
          </div>
          <button onclick="nextQuestion()">Next Question</button>
        `;
      }
    }

    function nextQuestion() {
      const selectedOption = document.querySelector('input[name="answer"]:checked');

      if (!selectedOption) {
        alert("Please select an option!");
        return;
      }

      const answer = selectedOption.value;
      if (answer === questions[currentQuestionIndex].answer) {
        correctAnswers++;
      }

      currentQuestionIndex++;

      if (currentQuestionIndex === questions.length) {
        showResults();
      } else {
        renderQuestion();
      }
    }

    function showResults() {
      const percentage = Math.round((correctAnswers / questions.length) * 100);

      quizContainer.innerHTML = `
        <div class="result-container">
          <div class="circle-progress">
            <div class="percentage-text">${percentage}%</div>
          </div>
          <h3>You scored: ${percentage}%</h3>
          <button onclick="saveResultsToDatabase(${percentage})">Save Results</button><br><br>
          <a href="dashboard.php">Back to Dashboard</a>
        </div>
      `;
         
      const circleProgress = document.querySelector(".circle-progress");
    circleProgress.style.background = `conic-gradient(#c40094 ${percentage}%, #555 0%)`;
    }

    function saveResultsToDatabase(percentage) {
      fetch("save_results.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({ user_id: userId, percentage }),
      })
        .then(response => response.text())
        .then(data => alert("Results saved successfully!"))
        .catch(err => console.error(err));
    }

    renderQuestion();
  </script>

</body>
</html>
