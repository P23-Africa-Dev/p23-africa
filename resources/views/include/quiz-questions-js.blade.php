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
    const questions = @json(array_map(function ($item) {
        return [
            'question' => $item['question'],
            'options' => ['A' => $item['a'], 'B' => $item['b'], 'C' => $item['c']],
        ];
    }, __('quiz.questions')));

    let currentQuestion = 0;
    let selectedAnswers = [];

    function loadQuestion() {
        const q = questions[currentQuestion];
        document.getElementById("questionText").innerText = q.question;
        document.getElementById("questionNumber").innerText = String(currentQuestion + 1).padStart(2, '0');
        document.getElementById("progressText").innerText = @json(__('quiz.progress')).replace(':current', currentQuestion + 1).replace(':total', questions.length);
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
                        <span class="challenge-label">${@json(__('quiz.ch1'))}</span>
                        <input type="text" id="challenge1" placeholder="${@json(__('quiz.ch1_ph'))}" class="challenge-input-with-label" />
                    </div>
                    <div class="challenge-box">
                        <span class="challenge-label">${@json(__('quiz.ch2'))}</span>
                        <input type="text" id="challenge2" placeholder="${@json(__('quiz.ch2_ph'))}" class="challenge-input-with-label" />
                    </div>
                    <div class="challenge-box">
                        <span class="challenge-label">${@json(__('quiz.ch3'))}</span>
                        <input type="text" id="challenge3" placeholder="${@json(__('quiz.ch3_ph'))}" class="challenge-input-with-label" />
                    </div>
                </div>
            `;
            nextBtn.disabled = false;
            nextBtn.textContent = @json(__('quiz.see_results'));
        }
    }

    document.getElementById("nextBtn").addEventListener("click", function() {
        if (currentQuestion < questions.length - 1) {
            currentQuestion++;
            loadQuestion();

            if (currentQuestion === questions.length - 1) {
                this.textContent = @json(__('quiz.continue_btn'));
            }
        } else {
            // Hide quiz and show email input
            document.getElementById("quizWrapper").style.display = "none";
            document.getElementById("emailFormWrapper").style.display = "block";
        }
    });


    document.getElementById("submitQuizBtn").addEventListener("click", async function() {
        const email = document.getElementById("userEmail").value.trim();
        const name = document.getElementById("userName").value.trim();

        if (!validateEmail(email)) {
            alert(@json(__('quiz.invalid_email')));
            return;
        }

        if (name.length < 2) {
            alert(@json(__('quiz.invalid_name')));
            return;
        }

        this.disabled = true;
        this.innerHTML = `<span class="spinner"></span> {{ __('quiz.analysing') }}`;

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
                    email,
                    name
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
