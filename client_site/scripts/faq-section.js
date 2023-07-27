const questions = document.getElementsByClassName("question");
const answers = document.getElementsByClassName("answer");
const arrowIcons = document.querySelectorAll(".faq-list .question i");

for(let i = 0 ; i<questions.length;i++) {
    questions[i].addEventListener('click', () => {
        answers[i].classList.toggle('answer-opened');
        arrowIcons[i].classList.toggle("arrow-rotate-down");
    });
    
};
