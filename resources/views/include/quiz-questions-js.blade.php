<script>
const questions = [
    {
        question: "How do you track potential leads and follow-ups?",
        options: {
            A: "We don’t track them",
            B: "Sometimes—manually or in spreadsheets",
            C: "We have a structured system"
        }
    },
    {
        question: "How consistently does your team follow up with warm leads?",
        options: {
            A: "Rarely",
            B: "Occasionally",
            C: "Always—on a clear schedule"
        }
    },
    {
        question: "Do you know exactly where your sales drop off in the customer journey?",
        options: {
            A: "No idea",
            B: "A rough idea",
            C: "Yes—we’ve analysed it"
        }
    },
    {
        question: "Are your team’s targets clear and regularly reviewed?",
        options: {
            A: "No",
            B: "Kind Of",
            C: "Yes—targets are tracked and discussed"
        }
    },
    {
        question: "Do you have simple tools to help your team stay organised?",
        options: {
            A: "Not Really",
            B: "We try—but it’s messy",
            C: "Yes—everyone uses them"
        }
    },
    {
        question: "How often do you review your sales and marketing performance?",
        options: {
            A: "Rarely or never",
            B: "Occasionally",
            C: "Monthly or weekly"
        }
    },
    {
        question: "Do you feel confident that your business is structured for growth?",
        options: {
            A: "Not at all",
            B: "Somewhat",
            C: "Definitely"
        }
    },
    {
        question: "What’s your biggest challenge right now? (optional)",
        options: {
            A: "Challenge 1: Type in your first challenge here",
            B: "Challenge 2: Type in your second challenge here",
            C: "Challenge 3: Type in your third challenge here"
        }
    }
];

let currentQuestion = 0;
let selectedAnswers = [];

function loadQuestion() {
    const q = questions[currentQuestion];
    document.getElementById("questionText").innerText = q.question;
    document.getElementById("questionNumber").innerText = String(currentQuestion + 1).padStart(2, '0');
    document.getElementById("progressText").innerText = `${currentQuestion + 1} of ${questions.length}`;
    document.getElementById("progressBar").style.width = `${((currentQuestion + 1) / questions.length) * 100}%`;

    const answerContainer = document.getElementById("answerOptions");
    answerContainer.innerHTML = "";

    for (const key in q.options) {
        const div = document.createElement("div");
        div.className = "answer-item";
        div.innerHTML = `
            <span class="answer-letter">${key}</span>
            <p class="answer-text">${q.options[key]}</p>
        `;
        div.addEventListener("click", function () {
            document.querySelectorAll(".answer-item").forEach(item => item.classList.remove("selected"));
            div.classList.add("selected");
            document.getElementById("nextBtn").disabled = false;
            selectedAnswers[currentQuestion] = key;
        });
        answerContainer.appendChild(div);
    }

    document.getElementById("nextBtn").disabled = true;
}

document.getElementById("nextBtn").addEventListener("click", function () {
    if (currentQuestion < questions.length - 1) {
        currentQuestion++;
        loadQuestion();
    } else {
        showResult();
    }
});

function showResult() {
    const counts = { A: 0, B: 0, C: 0 };
    selectedAnswers.forEach(ans => {
        if (ans && counts[ans] !== undefined) counts[ans]++;
    });

    let topChoice = 'A';
    if (counts['B'] > counts[topChoice]) topChoice = 'B';
    if (counts['C'] > counts[topChoice]) topChoice = 'C';

    // Redirect based on top selected option
    if (topChoice === 'A') {
        window.location.href = "/quiz-result-a";
    } else if (topChoice === 'B') {
        window.location.href = "/quiz-result-b";
    } else {
        window.location.href = "/quiz-result-c";
    }
}

window.onload = loadQuestion;
</script>
