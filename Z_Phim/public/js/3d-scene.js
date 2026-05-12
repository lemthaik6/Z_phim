// 3D Scene Setup using Three.js-like effects with Canvas

(function() {
    'use strict';

    // Initialize canvas background
    function initCanvasBackground() {
        const canvasContainer = document.getElementById('canvas-container');
        if (!canvasContainer) return;

        const canvas = document.createElement('canvas');
        const ctx = canvas.getContext('2d');

        function resizeCanvas() {
            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;
        }

        resizeCanvas();
        window.addEventListener('resize', resizeCanvas);

        canvasContainer.style.position = 'fixed';
        canvasContainer.style.top = '0';
        canvasContainer.style.left = '0';
        canvasContainer.style.width = '100%';
        canvasContainer.style.height = '100%';
        canvasContainer.style.zIndex = '1';
        canvasContainer.style.pointerEvents = 'none';

        canvasContainer.appendChild(canvas);

        // Particle system
        const particles = [];
        const particleCount = 50;

        class Particle {
            constructor() {
                this.x = Math.random() * canvas.width;
                this.y = Math.random() * canvas.height;
                this.vx = (Math.random() - 0.5) * 0.5;
                this.vy = (Math.random() - 0.5) * 0.5;
                this.radius = Math.random() * 2 + 1;
                this.opacity = Math.random() * 0.5 + 0.3;
                this.color = Math.random() > 0.5 ? '#6366f1' : '#8b5cf6';
            }

            update() {
                this.x += this.vx;
                this.y += this.vy;

                if (this.x < 0 || this.x > canvas.width) this.vx *= -1;
                if (this.y < 0 || this.y > canvas.height) this.vy *= -1;

                // Keep within bounds
                this.x = Math.max(0, Math.min(canvas.width, this.x));
                this.y = Math.max(0, Math.min(canvas.height, this.y));
            }

            draw(ctx) {
                ctx.fillStyle = this.color;
                ctx.globalAlpha = this.opacity;
                ctx.beginPath();
                ctx.arc(this.x, this.y, this.radius, 0, Math.PI * 2);
                ctx.fill();
            }
        }

        // Create particles
        for (let i = 0; i < particleCount; i++) {
            particles.push(new Particle());
        }

        // Animation loop
        function animate() {
            // Clear canvas with transparency
            ctx.clearRect(0, 0, canvas.width, canvas.height);

            // Draw background gradient
            const gradient = ctx.createLinearGradient(0, 0, canvas.width, canvas.height);
            gradient.addColorStop(0, 'rgba(15, 23, 42, 0.02)');
            gradient.addColorStop(0.5, 'rgba(99, 102, 241, 0.01)');
            gradient.addColorStop(1, 'rgba(139, 92, 246, 0.01)');
            ctx.fillStyle = gradient;
            ctx.fillRect(0, 0, canvas.width, canvas.height);

            // Reset global alpha
            ctx.globalAlpha = 1;

            // Draw connecting lines between particles
            for (let i = 0; i < particles.length; i++) {
                for (let j = i + 1; j < particles.length; j++) {
                    const dx = particles[i].x - particles[j].x;
                    const dy = particles[i].y - particles[j].y;
                    const distance = Math.sqrt(dx * dx + dy * dy);

                    if (distance < 150) {
                        ctx.strokeStyle = `rgba(99, 102, 241, ${0.1 * (1 - distance / 150)})`;
                        ctx.lineWidth = 1;
                        ctx.beginPath();
                        ctx.moveTo(particles[i].x, particles[i].y);
                        ctx.lineTo(particles[j].x, particles[j].y);
                        ctx.stroke();
                    }
                }
            }

            // Update and draw particles
            particles.forEach(particle => {
                particle.update();
                particle.draw(ctx);
            });

            requestAnimationFrame(animate);
        }

        animate();
    }

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initCanvasBackground);
    } else {
        initCanvasBackground();
    }

    // MovieCard3D class for 3D interactions
    window.MovieCard3D = class {
        constructor(cardElement) {
            this.card = cardElement;
            this.initListeners();
        }

        initListeners() {
            this.card.addEventListener('mousemove', (e) => this.onMouseMove(e));
            this.card.addEventListener('mouseleave', () => this.onMouseLeave());
            this.card.addEventListener('mouseenter', () => this.onMouseEnter());
        }

        onMouseEnter() {
            this.card.style.transition = 'transform 0.6s cubic-bezier(0.23, 1, 0.320, 1)';
        }

        onMouseMove(event) {
            const rect = this.card.getBoundingClientRect();
            const x = event.clientX - rect.left;
            const y = event.clientY - rect.top;

            // Calculate rotation
            const rotateX = (y - rect.height / 2) / 15;
            const rotateY = (x - rect.width / 2) / 15;

            // Calculate lighting
            const lightX = (x / rect.width) * 100;
            const lightY = (y / rect.height) * 100;

            this.card.style.transform = `perspective(1200px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) translateZ(20px)`;
            this.card.style.boxShadow = `
                ${(x - rect.width / 2) / 10}px ${(y - rect.height / 2) / 10}px 40px rgba(99, 102, 241, 0.3),
                0 0 60px rgba(99, 102, 241, 0.15)
            `;
        }

        onMouseLeave() {
            this.card.style.transform = 'perspective(1200px) rotateX(0) rotateY(0) translateZ(0)';
            this.card.style.boxShadow = '0 10px 30px rgba(0, 0, 0, 0.2)';
        }
    };

})();
