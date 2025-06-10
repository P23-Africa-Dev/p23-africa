<style>
    .spinner {
        border: 3px solid #f3f3f3;
        border-top: 3px solid #333;
        border-radius: 50%;
        width: 16px;
        height: 16px;
        animation: spin 0.8s linear infinite;
        display: inline-block;
        vertical-align: middle;
        margin-right: 8px;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }
</style>

<script>
    const questions = [{
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

        const nextBtn = document.getElementById("nextBtn");

        if (currentQuestion < 7) {
            for (const key in q.options) {
                const div = document.createElement("div");
                div.className = "answer-item";
                div.innerHTML = `
                    <span class="answer-letter">${key}</span>
                    <p class="answer-text">${q.options[key]}</p>
                `;
                div.onclick = function() {
                    document.querySelectorAll(".answer-item").forEach(item => item.classList.remove("selected"));
                    div.classList.add("selected");
                    nextBtn.disabled = false;
                    selectedAnswers[currentQuestion] = key;
                };
                answerContainer.appendChild(div);
            }
            nextBtn.disabled = true;
            nextBtn.textContent = "→";
        } else {
            // Final question (challenge input)
            answerContainer.innerHTML = `
                <div class="challenge-wrapper">
                    <div class="challenge-box">
                        <span class="challenge-label">Challenge 1:</span>
                        <input type="text" id="challenge1" placeholder="Type in your first challenge here" class="challenge-input-with-label" />
                    </div>
                    <div class="challenge-box">
                        <span class="challenge-label">Challenge 2:</span>
                        <input type="text" id="challenge2" placeholder="Type in your second challenge here" class="challenge-input-with-label" />
                    </div>
                    <div class="challenge-box">
                        <span class="challenge-label">Challenge 3:</span>
                        <input type="text" id="challenge3" placeholder="Type in your third challenge here" class="challenge-input-with-label" />
                    </div>
                </div>
            `;
            nextBtn.disabled = false;
            nextBtn.textContent = "See Results →";
        }
    }

    document.getElementById("nextBtn").addEventListener("click", function() {
        if (currentQuestion < questions.length - 1) {
            currentQuestion++;
            loadQuestion();

            if (currentQuestion === questions.length - 1) {
                this.textContent = "Continue →";
            }
        } else {
            // Hide quiz and show email input
            document.getElementById("quizWrapper").style.display = "none";
            document.getElementById("emailFormWrapper").style.display = "block";
        }
    });


    document.getElementById("submitQuizBtn").addEventListener("click", async function() {
        const email = document.getElementById("userEmail").value.trim();

        // if (!validateEmail(email)) {
        //     alert("Please enter a valid email address.");
        //     return;
        // }

        this.disabled = true;
        this.innerHTML = `<span class="spinner"></span> Analysing...`;

        const challenge1 = document.getElementById("challenge1")?.value ?? '';
        const challenge2 = document.getElementById("challenge2")?.value ?? '';
        const challenge3 = document.getElementById("challenge3")?.value ?? '';

        const counts = {
            A: 0,
            B: 0,
            C: 0
        };
        selectedAnswers.slice(0, 7).forEach(ans => {
            if (ans && counts.hasOwnProperty(ans)) counts[ans]++;
        });

        const topChoice = Object.entries(counts).sort((a, b) => b[1] - a[1])[0][0];

        const label = topChoice === 'A' ? 'FRAGILE FUNNEL' :
            topChoice === 'B' ? 'HIDDEN POWERHOUSE' : 'CLOUDY CLIMBER';

        try {
            const response = await fetch("/submit-challenge", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute(
                        "content")
                },
                body: JSON.stringify({
                    challenge1,
                    challenge2,
                    challenge3,
                    label,
                    email
                })
            });

            if (!response.ok) {
                throw new Error("Submission failed");
            }

            // Redirect based on result
            if (topChoice === 'A') {
                window.location.href = "/quiz/result/fragile-funnel";
            } else if (topChoice === 'B') {
                window.location.href = "/quiz/result/hidden-powerhouse";
            } else {
                window.location.href = "/quiz/result/cloudy-climber";
            }

        } catch (error) {
            console.error("Submission error:", error);
            alert("Something went wrong. Please try again.");
            this.innerHTML = "Submit & See Results";
            this.disabled = false;
        }
    });

    function validateEmail(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    }


    window.onload = loadQuestion;
</script>
