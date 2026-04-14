// Three.js 3D Scene Manager
class Scene3D {
    constructor(containerId = 'canvas-container') {
        this.container = document.getElementById(containerId);
        this.width = window.innerWidth;
        this.height = window.innerHeight;
        
        // Scene setup
        this.scene = new THREE.Scene();
        this.camera = new THREE.PerspectiveCamera(75, this.width / this.height, 0.1, 10000);
        this.renderer = new THREE.WebGLRenderer({ antialias: true, alpha: true });
        
        this.setupRenderer();
        this.createLights();
        this.createBackground();
        this.addEventListeners();
        this.animate();
    }

    setupRenderer() {
        this.renderer.setSize(this.width, this.height);
        this.renderer.setClearColor(0x000000, 0.1);
        this.renderer.shadowMap.enabled = true;
        this.container.appendChild(this.renderer.domElement);
        this.camera.position.z = 5;
    }

    createLights() {
        // Ambient Light
        const ambientLight = new THREE.AmbientLight(0xffffff, 0.6);
        this.scene.add(ambientLight);

        // Point Light 1
        const pointLight1 = new THREE.PointLight(0x6366f1, 1.2, 100);
        pointLight1.position.set(5, 5, 5);
        pointLight1.castShadow = true;
        this.scene.add(pointLight1);

        // Point Light 2
        const pointLight2 = new THREE.PointLight(0x8b5cf6, 1, 100);
        pointLight2.position.set(-5, -5, 5);
        this.scene.add(pointLight2);

        // Directional Light
        const directionalLight = new THREE.DirectionalLight(0x06b6d4, 0.8);
        directionalLight.position.set(10, 10, 5);
        directionalLight.castShadow = true;
        this.scene.add(directionalLight);
    }

    createBackground() {
        // Floating Particles
        const particlesGeometry = new THREE.BufferGeometry();
        const particleCount = 1000;
        const positionArray = new Float32Array(particleCount * 3);

        for (let i = 0; i < particleCount * 3; i += 3) {
            positionArray[i] = (Math.random() - 0.5) * 50;
            positionArray[i + 1] = (Math.random() - 0.5) * 50;
            positionArray[i + 2] = (Math.random() - 0.5) * 50;
        }

        particlesGeometry.setAttribute('position', new THREE.BufferAttribute(positionArray, 3));

        const particlesMaterial = new THREE.PointsMaterial({
            color: 0x6366f1,
            size: 0.1,
            opacity: 0.5,
            transparent: true,
            sizeAttenuation: true
        });

        this.particles = new THREE.Points(particlesGeometry, particlesMaterial);
        this.scene.add(this.particles);

        // Animated Torus
        const torusGeometry = new THREE.TorusGeometry(4, 1.5, 16, 100);
        const torusMaterial = new THREE.MeshStandardMaterial({
            color: 0x8b5cf6,
            metalness: 0.7,
            roughness: 0.2,
            emissive: 0x8b5cf6,
            emissiveIntensity: 0.3
        });

        this.torus = new THREE.Mesh(torusGeometry, torusMaterial);
        this.torus.position.z = -5;
        this.torus.rotation.x = 0.5;
        this.scene.add(this.torus);

        // Animated Sphere
        const sphereGeometry = new THREE.IcosahedronGeometry(1.2, 4);
        const sphereMaterial = new THREE.MeshStandardMaterial({
            color: 0x06b6d4,
            metalness: 0.8,
            roughness: 0.1,
            emissive: 0x06b6d4,
            emissiveIntensity: 0.5
        });

        this.sphere = new THREE.Mesh(sphereGeometry, sphereMaterial);
        this.sphere.position.set(3, 3, -5);
        this.scene.add(this.sphere);
    }

    addEventListeners() {
        window.addEventListener('resize', () => this.onWindowResize());
        document.addEventListener('mousemove', (e) => this.onMouseMove(e));
    }

    onWindowResize() {
        this.width = window.innerWidth;
        this.height = window.innerHeight;
        this.camera.aspect = this.width / this.height;
        this.camera.updateProjectionMatrix();
        this.renderer.setSize(this.width, this.height);
    }

    onMouseMove(event) {
        const x = (event.clientX / this.width) * 2 - 1;
        const y = -(event.clientY / this.height) * 2 + 1;

        if (this.torus) {
            this.torus.rotation.x += y * 0.005;
            this.torus.rotation.y += x * 0.005;
        }

        if (this.sphere) {
            this.sphere.position.x = 3 + x * 2;
            this.sphere.position.y = 3 + y * 2;
        }
    }

    animate() {
        requestAnimationFrame(() => this.animate());

        // Animate particles
        if (this.particles) {
            this.particles.rotation.x += 0.0001;
            this.particles.rotation.y += 0.0001;
        }

        // Animate torus
        if (this.torus) {
            this.torus.rotation.z += 0.001;
        }

        // Animate sphere
        if (this.sphere) {
            this.sphere.rotation.x += 0.002;
            this.sphere.rotation.y += 0.003;
        }

        this.renderer.render(this.scene, this.camera);
    }
}

// Initialize 3D Scene
let scene3D;
document.addEventListener('DOMContentLoaded', () => {
    // Only initialize if canvas container exists
    if (document.getElementById('canvas-container')) {
        // Load Three.js from CDN first
        const script = document.createElement('script');
        script.src = 'https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js';
        script.onload = () => {
            scene3D = new Scene3D();
        };
        document.head.appendChild(script);
    }
});

// Movie Card 3D Interaction
class MovieCard3D {
    constructor(cardElement) {
        this.card = cardElement;
        this.initListeners();
    }

    initListeners() {
        this.card.addEventListener('mousemove', (e) => this.onMouseMove(e));
        this.card.addEventListener('mouseleave', () => this.onMouseLeave());
    }

    onMouseMove(event) {
        const rect = this.card.getBoundingClientRect();
        const x = event.clientX - rect.left;
        const y = event.clientY - rect.top;

        const rotateX = (y - rect.height / 2) / 10;
        const rotateY = (x - rect.width / 2) / 10;

        this.card.style.transform = `perspective(1200px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) translateZ(20px)`;

        // Light effect
        const lightX = (x / rect.width) * 100;
        const lightY = (y / rect.height) * 100;
        this.card.style.setProperty('--light-x', `${lightX}%`);
        this.card.style.setProperty('--light-y', `${lightY}%`);
    }

    onMouseLeave() {
        this.card.style.transform = 'perspective(1200px) rotateX(0) rotateY(0) translateZ(0)';
    }
}

// Initialize movie cards
document.addEventListener('DOMContentLoaded', () => {
    const movieCards = document.querySelectorAll('.movie-card-3d');
    movieCards.forEach(card => new MovieCard3D(card));
});

// Stat Counter Animation
class CounterAnimation {
    constructor(element, target) {
        this.element = element;
        this.target = target;
        this.current = 0;
        this.speed = this.target / 50;
    }

    animate() {
        this.current += this.speed;
        if (this.current >= this.target) {
            this.current = this.target;
        }
        this.element.textContent = Math.floor(this.current).toLocaleString();
        if (this.current < this.target) {
            requestAnimationFrame(() => this.animate());
        }
    }

    start() {
        this.animate();
    }
}

// Initialize counters on viewport intersection
document.addEventListener('DOMContentLoaded', () => {
    const statNumbers = document.querySelectorAll('.stat-number');
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const target = parseInt(entry.target.dataset.target) || 0;
                const counter = new CounterAnimation(entry.target, target);
                counter.start();
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.5 });

    statNumbers.forEach(num => observer.observe(num));
});

// Smooth scroll reveal
document.addEventListener('DOMContentLoaded', () => {
    const cards = document.querySelectorAll('.card-3d, .movie-card-3d, .stat-card');
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, { threshold: 0.1 });

    cards.forEach(card => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(card);
    });
});

// Interactive background grid
window.addEventListener('load', () => {
    const canvas = document.createElement('canvas');
    const ctx = canvas.getContext('2d');
    canvas.width = window.innerWidth;
    canvas.height = window.innerHeight;

    const grid = {
        x: 50,
        y: 50,
        offsetX: 0,
        offsetY: 0
    };

    const drawGrid = () => {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        ctx.strokeStyle = 'rgba(99, 102, 241, 0.05)';
        ctx.lineWidth = 1;

        for (let x = grid.offsetX; x < canvas.width; x += grid.x) {
            ctx.beginPath();
            ctx.moveTo(x, 0);
            ctx.lineTo(x, canvas.height);
            ctx.stroke();
        }

        for (let y = grid.offsetY; y < canvas.height; y += grid.y) {
            ctx.beginPath();
            ctx.moveTo(0, y);
            ctx.lineTo(canvas.width, y);
            ctx.stroke();
        }

        grid.offsetX += 0.5;
        grid.offsetY += 0.5;

        if (grid.offsetX > grid.x) grid.offsetX = 0;
        if (grid.offsetY > grid.y) grid.offsetY = 0;

        requestAnimationFrame(drawGrid);
    };

    // Only start if not using WebGL background
    if (!document.getElementById('canvas-container')) {
        drawGrid();
    }
});
