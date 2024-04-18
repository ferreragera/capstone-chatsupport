<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Typing Animation</title>
<style>
body {
    font-family: Arial, sans-serif;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    margin: 0;
    background-color: #f0f0f0;
}

#text-container {
    overflow: hidden;
}

#text {
    display: inline-block;
    white-space: nowrap;
    overflow: hidden;
    animation: typing 2s steps(30, end);
}

@keyframes typing {
    from {
        width: 0;
    }
}
</style>
</head>
<body>
<div id="text-container">
    <span id="text">Your text here</span>
</div>
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const text = document.getElementById('text');
    const textContent = text.textContent;
    text.textContent = '';

    for (let i = 0; i < textContent.length; i++) {
        const letterSpan = document.createElement('span');
        letterSpan.textContent = textContent[i];
        letterSpan.style.animation = `typing 2s steps(30, end) ${i * 0.1}s forwards`;
        text.appendChild(letterSpan);
    }
});
</script>
</body>
</html>
