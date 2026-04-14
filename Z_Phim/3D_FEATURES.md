# Z-PHIM 3D - Website Quản Lý Rạp Phim Ba Chiều

## 🎬 Tính Năng Mới 3D

### Dashboard Admin 3D (`/admin/3d`)
- **Three.js Background**: Cảnh 3D động với các hình khối tương tác
- **Interactive Stats Cards**: Thẻ thống kê với hiệu ứng 3D, hover effects
- **Animated Counter**: Số liệu tự động đếm với animation mượt mà
- **Particle System**: Hệ thống hạt bay động trong background
- **Lighting System**: Hệ thống ánh sáng (Ambient, Point, Directional)

### Movie Gallery 3D (`/movies-3d`)
- **3D Movie Cards**: Thẻ phim với hiệu ứng 3D perspective
- **Interactive Rotation**: Các thẻ xoay theo chuyển động chuột (3D tilt effect)
- **Dynamic Lighting**: Ánh sáng động theo vị trí con chuột
- **Genre Filtering**: Lọc phim theo thể loại với transition animation
- **3D Canvas Background**: Cảnh 3D dengan floating particles

## 🎨 Công Nghệ Sử Dụng

### Frontend
- **Three.js** - Thư viện 3D WebGL JavaScript
- **Tailwind CSS** - Styling utility-first
- **Vanilla JavaScript** - JavaScript thuần cho animation và interaction

### Backend
- **Laravel 12** - Framework PHP
- **MySQL** - Database
- **RESTful API** - Giao tiếp dữ liệu

## 📁 Cấu Trúc File Mới

```
resources/
├── css/
│   └── 3d.css              # Styling cho elements 3D
├── js/
│   └── 3d-scene.js         # Three.js scene management & animations
└── views/
    ├── admin/
    │   └── dashboard-3d.blade.php    # Dashboard admin 3D
    └── movies/
        └── index-3d.blade.php        # Movie gallery 3D
```

## 🚀 Routes Mới

### User Routes
- `GET /movies-3d` - Trang danh mục phim 3D (auth required)

### Admin Routes
- `GET /admin/3d` - Dashboard admin 3D (admin only)

## 🎯 Tính Năng 3D Chi Tiết

### 1. Three.js Scene (`3d-scene.js`)
```javascript
- Scene3D class: Quản lý cảnh 3D chính
- createLights(): Tạo hệ thống ánh sáng
- createBackground(): Tạo particles, torus, sphere tương tác
- animate(): Vòng lặp animation chính
```

### 2. Interactive 3D Cards
```javascript
- MovieCard3D class: Xử lý perspective 3D cho thẻ phim
- CounterAnimation: Animate số liệu tăng
- Intersection Observer: Lazy load animation
```

### 3. Styling 3D (`3d.css`)
- `@keyframes` cho animation float, shine, gradient-shift
- `perspective` CSS 3D transforms
- `transform-style: preserve-3d` cho effect 3D sâu
- Glass morphism background effect

## 🎮 Interactivity

### Mouse Tracking
- Cards tilt theo vị trí chuột
- Ánh sáng động thay đổi theo mouse position
- Particles di chuyển khi scroll

### Viewport Animation
- Cards animate vào viewport khi scroll
- Counter tăng khi phần tử nhìn thấy
- Staggered animations cho nhiều elements

### Hover Effects
- 3D rotation trên hover
- Glow shadow effects
- Scale transformations
- Shine animation overlay

## 🔧 Các Thành Phần 3D

### Dashboard Stats Cards
- Icon dengan gradient background
- Number với counter animation
- Glow effect khi hover
- 3D perspective tilt

### Movie Cards
- Poster image overlay
- Genre/Duration info display
- Button dengan shimmer effect
- Full 3D perspective interaction

### Background Effects
- Particle system (1000 particles)
- Animated torus geometry
- Interactive icosahedron sphere
- Grid background pattern (animated)

## 📱 Responsive Design

- Mobile: 1 cột grid
- Tablet: 2 cột grid
- Desktop: 3-4 cột grid
- Tất cả 3D effects vẫn hoạt động trên mobile

## 🚀 Performance Optimization

- **Lazy Loading**: Chỉ load Three.js khi cần
- **RequestAnimationFrame**: Sử dụng rAF cho smooth 60fps
- **Canvas Optimization**: Antialiasing, shadow maps tối ưu
- **CSS Hardware Acceleration**: Transform & opacity animations

## 🎨 Color Scheme

```
Primary: #6366f1 (Indigo)
Secondary: #8b5cf6 (Purple)
Accent: #06b6d4 (Cyan)
Dark: #0f172a
Darker: #020617
Light Text: #94a3b8
```

## 💡 Cải Tiến Tương Lai

- [ ] WebGL shader effects
- [ ] 3D model loading (glTF/GLTF)
- [ ] Advanced particle effects
- [ ] Audio visualization
- [ ] Multi-user interaction
- [ ] VR support (WebXR API)

## 🛠️ Installation

```bash
# Installation không cần thêm packages ngoài
# Three.js được load từ CDN

# Chỉ cần giữ files mới:
# - resources/css/3d.css
# - resources/js/3d-scene.js
# - resources/views/admin/dashboard-3d.blade.php
# - resources/views/movies/index-3d.blade.php
```

## 📝 Ghi Chú

- Tất cả effects sử dụng CSS modern standards (CSS 3D Transforms)
- Fallback cho browsers cũ hơn sử dụng CSS transitions
- Progressive enhancement - site vẫn hoạt động nếu JavaScript disabled

## 👨‍💻 Developer

Được tạo với ❤️ sử dụng Three.js và Tailwind CSS

---

**Demo URLs:**
- Admin 3D: `http://127.0.0.1:8000/admin/3d`
- Movies 3D: `http://127.0.0.1:8000/movies-3d`
