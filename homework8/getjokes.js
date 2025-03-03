const jokeButton = document.getElementById('get-joke');
const jokeText = document.getElementById('joke-text');
const categorySelect = document.getElementById('category');

jokeButton.addEventListener('click', function() {
  const category = categorySelect.value;

  fetch(`https://v2.jokeapi.dev/joke/${category}`)
    .then(response => {
      if (!response.ok) {
        throw new Error('Network response was not ok');
      }
      return response.json();
    })
    .then(data => {
      if (data.type === 'single') {
        jokeText.textContent = data.joke;
      } else {
        jokeText.textContent = `${data.setup} - ${data.delivery}`;
      }
    })
    .catch(error => {
      jokeText.textContent = "ERROR";
      console.error('ERROR FETCHING JOKE: ', error);
    });
});
