Thành phần tái sử dụng:
- /components/                  : Navbar, footer, form feedback dùng chung
- /assets/css/                  : CSS từng phần + style chung
- /assets/js/                   : Script JS từng phần + script chung
- /data/tags.json               : Metadata bài viết (title, path, tags...)

Công nghệ:
- HTML, CSS, JavaScript
- jQuery (cho feedback và xử lý dữ liệu từ JSON)
- JSON (lưu metadata bài viết)

Ghi chú:
- Sử dụng JavaScript hoặc jQuery để load nội dung động từ `tags.json`
- Có thể mở rộng với các framework như React, Vue trong tương lai nếu cần



/PersonalBlogWebsite
└── /blog
    ├── index.html                             # Trang chủ
    │
    ├── /news                                  # Tin tức / cập nhật
    │   └── index.html         
    │           
    ├── /tools                                 # Các công cụ tự viết hoặc tổng hợp
    │   └── index.html          
    │          
    ├── /ctf                                   # CTF writeups
    │   └── index.html  
    │                  
    ├── /my-roadmap                             # Lộ trình học tập cá nhân
    │   └── index.html 
    │                   
    ├── /portfolio                             # Portfolio cá nhân
    │   └── index.html                    
    │
    ├── /topics                               # Chuyên mục kiến thức nền tảng
    │   ├── index.html                        # Trang chọn category
    │   │
    │   ├── /network
    │   │   ├── index.html                    # Chọn tag (VD: TCP, DNS...)
    │   │   ├── tcp.html
    │   │   ├── dns.html
    │   │   └── network.css / network.js
    │   │
    │   ├── /security
    │   │   ├── index.html
    │   │   ├── xss.html
    │   │   ├── csrf.html
    │   │   └── security.css / security.js
    │   │
    │   ├── /cryptography
    │   │   ├── index.html
    │   │   ├── aes.html
    │   │   ├── rsa.html
    │   │   └── crypto.css / crypto.js
    │   │
    │   ├── /mathematics
    │   │   ├── index.html
    │   │   ├── calculus.html
    │   │   ├── discrete.html
    │   │   └── math.css / math.js
    │   │
    │   └── /operating-systems
    │       ├── index.html
    │       ├── memory.html
    │       ├── filesystem.html
    │       └── os.css / os.js
    │
    ├── /components
    │   ├── navbar.html
    │   ├── footer.html
    │   ├── navbar-left.html        # Navbar bên trái
    │   ├── navbar-right.html       # Navbar bên phải
    │   └── feedback-form.html      # Phần feedback
    │
    ├── /assets
    │   ├── /css
    │   │   ├── /common                   # CSS chung toàn site
    │   │   │   └── style.css
    │   │   ├── /news                                  
    │   │   │   └── news.css                    
    │   │   ├── /tools                                 
    │   │   │   └── tools.css                    
    │   │   ├── /ctf                                   
    │   │   │   └── ctf.css                    
    │   │   ├── /my-roadmap                             
    │   │   │   └── my-roadmap.css                    
    │   │   └── /portfolio                             
    │   │       └── portfolio.css
    │   │
    │   ├── /scss
    │   │   ├── /common                   # sCSS chung toàn site
    │   │   │   └── style.scss
    │   │   ├── /news                                  
    │   │   │   ├── news.scss                    
    │   │   │   └── _responsive-news.scss                    
    │   │   ├── /tools                                 
    │   │   │   ├── tools.scss                    
    │   │   │   └── _responsive-tools.scss                    
    │   │   ├── /ctf                                   
    │   │   │   ├── ctf.scss                    
    │   │   │   └── _responsive-ctf.scss                    
    │   │   ├── /my-roadmap                             
    │   │   │   ├── my-roadmap.scss                    
    │   │   │   └── _responsive-my-roadmap.scss                    
    │   │   └── /portfolio                             
    │   │       ├── portfolio.scss
    │   │       └── _responsive-portfolio.scss
    │   │
    │   ├── /js
    │   │   ├── 
    │   │   ├── navbar.js
    │   │   ├── footer.js
    │   │   ├── feedback.js
    │   │   ├── news.js
    │   │   └── roadmap.js
    │   │   ├── /common                   # js chung toàn site
    │   │   │   ├── script.js                 # JS chung (navbar, darkmode,...)
    │   │   │   ├── navbar.js
    │   │   │   ├── footer.js
    │   │   │   └── feedback.js
    │   │   ├── /news                                  
    │   │   │   └── news.js                    
    │   │   ├── /tools                                 
    │   │   │   └── tools.js                    
    │   │   ├── /ctf                                   
    │   │   │   └── ctf.js                    
    │   │   ├── /my-roadmap                             
    │   │   │   └── my-roadmap.js                    
    │   │   └── /portfolio                             
    │   │       └── portfolio.js
    │   │
    │   ├── /icons
    │   │   └── blog.icon
    │   │
    │   ├── /fonts
    │   │   ├── /DancingScript                                  
    │   │   └── /Hack                                 
    │   │
    │   ├── /videos
    │   │
    │   └── /images
    │       ├── /news                                  
    │       │           
    │       ├── /tools                                 
    │       │          
    │       ├── /ctf                                   
    │       │                  
    │       ├── /my-roadmap                             
    │       │                   
    │       ├── /portfolio                            
    │       │
    │       └── /topics                               
    │
    ├── /data
    │   └── tags.json                     # Metadata bài viết: title, slug, path, tags
    │
    └── feedback.html                     # (tùy chọn) trang feedback độc lập
