const canvas = document.getElementById('article--trongate_canvas');
const ctx = canvas.getContext('2d');

// Set canvas size to match the window
canvas.width = window.innerWidth;
canvas.height = window.innerHeight;

// Array to hold star objects
let stars = [];

// Create stars
function createStars() {
    for (let i = 0; i < 200; i++) {
        stars.push({
            x: Math.random() * canvas.width,
            y: Math.random() * canvas.height,
            radius: Math.random() * 2 + 1, // Random radius
            opacity: Math.random() // Random opacity
        });
    }
}

// Draw the stars
function drawStars() {
    stars.forEach(star => {
        ctx.beginPath();
        ctx.arc(star.x, star.y, star.radius, 0, Math.PI * 2);
        ctx.fillStyle = `rgba(255, 255, 255, ${star.opacity})`;
        ctx.fill();
    });
}

// Animation loop
function animate() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);

    drawStars();

    stars.forEach(star => {
        star.opacity += (Math.random() * 0.02 - 0.01); // Change opacity slightly
        star.opacity = Math.max(0, Math.min(1, star.opacity)); // Clamp opacity between 0 and 1
    });

    requestAnimationFrame(animate);
}

// Adjust canvas size when window is resized
window.addEventListener('resize', () => {
    canvas.width = window.innerWidth;
    canvas.height = window.innerHeight;
    createStars(); // Recreate stars on resize
});

createStars();
animate();