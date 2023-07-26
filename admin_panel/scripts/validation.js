function validateYouTubeURL() {
  const youtubeURLInput = document.getElementById('youtubeURL').value;

  if (youtubeURLInput ==null || youtubeURLInput === "") {
    return true;
  }

  // Regular expression pattern for a valid YouTube URL
  const youtubeRegex = /^(https?:\/\/)?(www\.)?youtube\.com\/watch\?v=[\w-]{11}$/;

  // Check if the input matches the pattern
  if (!youtubeRegex.test(youtubeURLInput)) {
    alert("Please enter a valid YouTube video URL.");
    return false; 
  }

  // If the URL is valid, you can further process or extract the video ID from the URL.
  const videoId = youtubeURLInput.split('v=')[1];
  return true; 
}