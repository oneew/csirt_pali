# CSIRTAmericas Website Clone

A complete clone of the CSIRTAmericas website (https://csirtamericas.org/) built with modern web technologies.

## 🚀 Features

- **Responsive Design**: Fully responsive layout that works on all devices
- **Modern UI/UX**: Clean, professional design with smooth animations
- **Interactive Elements**: Hover effects, smooth scrolling, and dynamic content
- **Accessibility**: Keyboard navigation and screen reader support
- **Performance Optimized**: Fast loading with optimized assets
- **Cross-browser Compatible**: Works on all modern browsers

## 📁 Project Structure

```
csirtamericas-clone/
├── index.html          # Main HTML file
├── css/
│   └── styles.css      # Main stylesheet
├── js/
│   └── main.js         # JavaScript functionality
├── images/
│   ├── logo.svg        # Website logo
│   └── hero-illustration.svg  # Hero section illustration
└── README.md           # This file
```

## 🛠️ Technologies Used

- **HTML5**: Semantic markup
- **CSS3**: Modern styling with Flexbox and Grid
- **JavaScript (ES6+)**: Interactive functionality
- **Font Awesome**: Icons
- **Google Fonts**: Inter font family
- **SVG**: Scalable vector graphics for logos and illustrations

## 🎨 Design Features

### Color Scheme
- Primary: `#2563eb` (Blue)
- Secondary: `#667eea` to `#764ba2` (Gradient)
- Text: `#1e293b` (Dark gray)
- Background: `#f8fafc` (Light gray)

### Typography
- Font Family: Inter (Google Fonts)
- Weights: 300, 400, 500, 600, 700

### Components
- Fixed header with navigation
- Hero section with gradient background
- Statistics section with animated counters
- Service cards with hover effects
- Requirements section
- About section
- Footer with links

## 🚀 Getting Started

### Prerequisites
- A modern web browser (Chrome, Firefox, Safari, Edge)
- No additional software required

### Installation

1. **Clone or Download** the project files
2. **Open** `index.html` in your web browser
3. **Enjoy** the website!

### Alternative: Using a Local Server

For the best experience, you can run the website on a local server:

#### Using Python (if installed):
```bash
# Python 3
python -m http.server 8000

# Python 2
python -m SimpleHTTPServer 8000
```

#### Using Node.js (if installed):
```bash
# Install http-server globally
npm install -g http-server

# Run the server
http-server
```

Then open `http://localhost:8000` in your browser.

## 📱 Responsive Breakpoints

- **Desktop**: 1200px and above
- **Tablet**: 768px - 1199px
- **Mobile**: Below 768px

## 🎯 Key Features Implemented

### Navigation
- Fixed header with smooth scroll hiding
- Dropdown menus for community section
- Mobile hamburger menu
- Language selector (Spanish/English)

### Animations
- Smooth scrolling for anchor links
- Animated statistics counters
- Hover effects on service cards
- Parallax effect on hero section
- Loading animations

### Interactive Elements
- Mobile menu toggle
- Language selection
- CTA button functionality
- Info button alerts
- Login button placeholder

### Performance
- Optimized CSS and JavaScript
- Lazy loading for images
- Efficient animations using requestAnimationFrame
- Minimal external dependencies

## 🔧 Customization

### Changing Colors
Edit the CSS variables in `css/styles.css`:
```css
:root {
    --primary-color: #2563eb;
    --secondary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    --text-color: #1e293b;
    --background-color: #f8fafc;
}
```

### Adding Content
- **New Sections**: Add HTML in `index.html`
- **Styling**: Add CSS in `css/styles.css`
- **Functionality**: Add JavaScript in `js/main.js`

### Modifying Images
Replace the SVG files in the `images/` directory with your own assets.

## 🌐 Browser Support

- Chrome 60+
- Firefox 55+
- Safari 12+
- Edge 79+

## 📄 License

This project is a clone for educational purposes. The original website design and content belong to CSIRTAmericas.

## 🤝 Contributing

Feel free to fork this project and make improvements. Some ideas:
- Add more interactive features
- Implement a contact form
- Add more language support
- Create additional pages
- Optimize performance further

## 📞 Support

If you have any questions or need help with the project, please open an issue in the repository.

## 🔗 Original Website

Visit the original CSIRTAmericas website: https://csirtamericas.org/

---

**Note**: This is a static clone for demonstration purposes. The original website may have additional features and dynamic content that are not replicated here. 