<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
  <link rel="icon" href="{{ asset('assets/img/favicon/data-lake-logo.png') }}" type="image/png">

  <title>Data Lake</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    html, body {
      height: 100%;
      background-color: #000080;
      overflow: hidden;
      font-family:Helvetica, sans-serif;
    }

    #dynamic-canvas {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
    }

    .login-wrapper {
      position: relative;
      z-index: 1;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100%;
    }

    .login-card {
      /* background: linear-gradient(145deg, #ffffff,rgb(165, 196, 227)); */
      /* background:rgb(217, 221, 224); */
      background: white;
      padding: 2.5rem 2rem;
      border-radius: 1rem;
      box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
      width: 90%;
      max-width: 400px;
      text-align: center;
      transition: transform 0.3s ease;
    }

    /* .login-card:hover {
      transform: translateY(-10px);
    } */

    .login-card img {
      width: 80px;
      margin-bottom: 1rem;
      /* animation: fadeIn 1s ease forwards; */
    }

    /* @keyframes fadeIn {
      0% { opacity: 0; transform: scale(0.8); }
      100% { opacity: 1; transform: scale(1); }
    } */

    a{
      text-decoration: none;
      color: #007bff;
      font-weight: bold;
      transition: color 0.3s ease;
    }
    .animated-title {
      font-size: 2.2rem;
      font-weight: bold;
      color: #007bff;
      margin-bottom: 1rem;
      animation: fadeIn 1.2s ease forwards;
    }

    .btn-custom {
      display: inline-block;
      background: linear-gradient(145deg, #696cff, #696cff);
      border: none;
      width: 60%;
      text-align: center;
      border-radius: 10px;
      padding: 0.6rem 2rem;
      font-weight: 600;
      color: #fff;
      /* text-transform: uppercase; */
      font-size: 1rem;
      /* transition: background 0.3s ease, transform 0.2s ease; */
      cursor: pointer;
    }

    .btn-custom:hover {
      transform: translateY(-3px);
    }

  </style>
</head>
<body>
  <canvas id="dynamic-canvas"></canvas>

  <div class="container login-wrapper">
    <div class="login-card">
      <img src="{{ asset('assets/img/favicon/data-lake-logo.png') }}" alt="Logo" />
      <h2 class="animated-title">DATA LAKE</h2>
      <a href="{{ route('auth-login-basic')}}" class="btn-custom">Login</a>
    </div>
  </div>

  <script>
    const canvas = document.getElementById('dynamic-canvas');
    const ctx = canvas.getContext('2d');
    let width, height, nodes = [];

    const resizeCanvas = () => {
      width = canvas.width = window.innerWidth;
      height = canvas.height = window.innerHeight;
    };

    class Node {
      constructor(x, y) {
        this.x = x;
        this.y = y;
        this.radius = Math.random() * 5 + 2;
        this.opacity = 0;
        this.active = false;
        this.fadeIn = true;
      }

      draw() {
        if (!this.active) return;

        ctx.beginPath();
        ctx.arc(this.x, this.y, this.radius, 0, Math.PI * 2);
        ctx.fillStyle = `rgba(102, 178, 255, ${this.opacity})`;
        ctx.fill();
      }

      connectTo(otherNode) {
        if (!this.active || !otherNode.active) return;

        const dx = this.x - otherNode.x;
        const dy = this.y - otherNode.y;
        const dist = Math.sqrt(dx * dx + dy * dy);
        if (dist < 150) {
          ctx.beginPath();
          ctx.moveTo(this.x, this.y);
          ctx.lineTo(otherNode.x, otherNode.y);
          ctx.strokeStyle = `rgba(0, 123, 255, ${Math.min(this.opacity, otherNode.opacity)})`;
          ctx.stroke();
        }
      }

      toggleActive() {
        if (Math.random() < 0.01) {
          this.active = !this.active;
          this.fadeIn = this.active;
        }
      }

      updateOpacity() {
        if (this.active) {
          if (this.fadeIn) {
            this.opacity += 0.02;
            if (this.opacity >= 1) this.fadeIn = false;
          }
        } else {
          this.opacity -= 0.02;
          if (this.opacity <= 0) this.opacity = 0;
        }
      }
    }

    const generateNodes = () => {
      nodes = [];
      const nodeCount = Math.floor((width * height) / 10000);
      for (let i = 0; i < nodeCount; i++) {
        const x = Math.random() * width;
        const y = Math.random() * height;
        nodes.push(new Node(x, y));
      }
    };

    const draw = () => {
      ctx.clearRect(0, 0, width, height);

      nodes.forEach(node => {
        node.toggleActive();
        node.updateOpacity();
        node.draw();
      });

      nodes.forEach((node, i) => {
        nodes.slice(i + 1).forEach(otherNode => {
          node.connectTo(otherNode);
        });
      });

      requestAnimationFrame(draw);
    };

    window.addEventListener('resize', () => {
      resizeCanvas();
      generateNodes();
    });

    resizeCanvas();
    generateNodes();
    draw();
  </script>

  <script>
    @if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif
  </script>
</body>
</html>
