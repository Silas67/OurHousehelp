 <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600&family=DM+Sans:wght@300;400;500&display=swap');

        :root {
            --green-50: #E1F5EE;
            --green-100: #9FE1CB;
            --green-600: #0F6E56;
            --green-800: #085041;
            --amber-50: #FAEEDA;
            --amber-400: #BA7517;
            --cream: #FAF8F4;
            --ink: #1C1C1A;
            --muted: #6B6B67;
        }

        .ohh-page { font-family: 'DM Sans', sans-serif; color: var(--ink); }
        .ohh-display { font-family: 'Playfair Display', serif; }

        /* ── Hero ── */
        .ohh-hero {
            background: var(--cream);
            padding: 100px 0 80px;
            position: relative;
            overflow: hidden;
        }
        .ohh-hero::before {
            content: '';
            position: absolute;
            top: -80px; right: -80px;
            width: 420px; height: 420px;
            border-radius: 50%;
            background: var(--green-50);
            z-index: 0;
        }
        .ohh-hero::after {
            content: '';
            position: absolute;
            bottom: -60px; left: -60px;
            width: 280px; height: 280px;
            border-radius: 50%;
            background: var(--amber-50);
            z-index: 0;
        }
        @media (max-width: 768px) {
        .ohh-hero::before {
            display: none;
        },
        }

        @media (max-width: 768px) {
        .ohh-hero .d-flex.gap-3 {
            justify-content: center;
        }
        .ohh-hero h1,
        .ohh-hero-eyebrow,
        .ohh-hero-sub {
            text-align: center;
        }
        .ohh-hero-sub {
            margin-left: auto;
            margin-right: auto;
        }
        .ohh-hero-stats {
            justify-content: center;
        }
        }

        .ohh-hero .container { position: relative; z-index: 1; }
        .ohh-hero-eyebrow {
            display: inline-flex; align-items: center; gap: 8px;
            background: var(--green-50);
            color: var(--green-600);
            font-size: 13px; font-weight: 500; letter-spacing: 0.06em;
            padding: 6px 16px; border-radius: 999px;
            margin-bottom: 28px;
            text-transform: uppercase;
        }
        .ohh-hero h1 {
            font-family: 'Playfair Display', serif;
            font-size: clamp(38px, 5vw, 64px);
            font-weight: 500; line-height: 1.15;
            color: var(--ink);
            margin-bottom: 24px;
        }
        .ohh-hero h1 em { color: var(--green-600); font-style: italic; }
        .ohh-hero-sub {
            font-size: 17px; color: var(--muted); line-height: 1.7;
            max-width: 520px; margin-bottom: 40px; font-weight: 300;
        }
        .ohh-btn-primary {
            background: var(--green-600); color: #fff;
            padding: 14px 32px; border-radius: 8px;
            font-size: 15px; font-weight: 500;
            text-decoration: none; display: inline-block;
            transition: background 0.2s;
            border: none; cursor: pointer;
        }
        .ohh-btn-primary:hover { background: var(--green-800); color: #fff; }
        .ohh-btn-outline {
            background: transparent; color: var(--ink);
            padding: 14px 32px; border-radius: 8px;
            font-size: 15px; font-weight: 500;
            text-decoration: none; display: inline-block;
            border: 1.5px solid #C5C5BF;
            transition: border-color 0.2s, color 0.2s;
        }
        .ohh-btn-outline:hover { border-color: var(--green-600); color: var(--green-600); }
        .ohh-hero-stats {
            display: flex; gap: 40px; margin-top: 56px;
            padding-top: 40px; border-top: 1px solid #E5E5E0;
        }
        .ohh-stat-num {
            font-family: 'Playfair Display', serif;
            font-size: 32px; font-weight: 500; color: var(--ink);
            line-height: 1;
        }
        .ohh-stat-label { font-size: 13px; color: var(--muted); margin-top: 4px; }

        /* ── Trust bar ── */
        .ohh-trust {
            background: var(--green-600); color: #fff;
            padding: 20px 0;
        }
        .ohh-trust-inner {
            display: flex; align-items: center; justify-content: center;
            gap: 48px; flex-wrap: wrap;
        }
        .ohh-trust-item {
            display: flex; align-items: center; gap: 10px;
            font-size: 14px; font-weight: 400; opacity: 0.9;
        }
        .ohh-trust-item i { font-size: 18px; }

        /* ── About ── */
        .ohh-about { padding: 96px 0; background: #fff; }
        .ohh-section-tag {
            font-size: 12px; font-weight: 500; letter-spacing: 0.1em;
            text-transform: uppercase; color: var(--green-600);
            margin-bottom: 16px; display: block;
        }
        .ohh-section-heading {
            font-family: 'Playfair Display', serif;
            font-size: clamp(28px, 3.5vw, 44px);
            font-weight: 500; line-height: 1.2; color: var(--ink);
            margin-bottom: 20px;
        }
        .ohh-section-body {
            font-size: 16px; color: var(--muted);
            line-height: 1.8; font-weight: 300;
        }
        .ohh-about-card {
            background: var(--cream); border-radius: 16px;
            padding: 36px; height: 100%;
            border: 1px solid #EAE9E3;
            transition: border-color 0.2s, transform 0.2s;
        }
        .ohh-about-card:hover { border-color: var(--green-100); transform: translateY(-2px); }
        .ohh-about-icon {
            width: 48px; height: 48px; border-radius: 12px;
            background: var(--green-50);
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 20px; font-size: 22px; color: var(--green-600);
        }
        .ohh-about-card h4 {
            font-family: 'Playfair Display', serif;
            font-size: 20px; font-weight: 500; margin-bottom: 12px; color: var(--ink);
        }
        .ohh-about-card p { font-size: 15px; color: var(--muted); line-height: 1.7; margin: 0; }

        /* ── Services ── */
        .ohh-services { padding: 96px 0; background: var(--cream); }
        .ohh-service-card {
            background: #fff; border-radius: 16px;
            padding: 32px 28px;
            border: 1px solid #EAE9E3;
            transition: all 0.2s; height: 100%;
        }
        .ohh-service-card:hover {
            border-color: var(--green-600);
            box-shadow: 0 8px 32px rgba(15, 110, 86, 0.08);
            transform: translateY(-3px);
        }
        .ohh-service-icon {
            width: 52px; height: 52px; border-radius: 14px;
            background: var(--green-50); display: flex;
            align-items: center; justify-content: center;
            font-size: 24px; color: var(--green-600); margin-bottom: 20px;
        }
        .ohh-service-card h5 {
            font-family: 'Playfair Display', serif;
            font-size: 18px; font-weight: 500; margin-bottom: 10px; color: var(--ink);
        }
        .ohh-service-card p { font-size: 14px; color: var(--muted); line-height: 1.7; margin: 0; }

        /* ── How it works ── */
        .ohh-how { padding: 96px 0; background: #fff; }
        .ohh-step { display: flex; gap: 24px; margin-bottom: 48px; }
        .ohh-step:last-child { margin-bottom: 0; }
        .ohh-step-num {
            flex-shrink: 0;
            width: 44px; height: 44px; border-radius: 50%;
            background: var(--green-600); color: #fff;
            display: flex; align-items: center; justify-content: center;
            font-family: 'Playfair Display', serif;
            font-size: 18px; font-weight: 500;
        }
        .ohh-step-body h5 {
            font-family: 'Playfair Display', serif;
            font-size: 18px; font-weight: 500; margin-bottom: 8px; color: var(--ink);
        }
        .ohh-step-body p { font-size: 15px; color: var(--muted); line-height: 1.7; margin: 0; }
        .ohh-how-visual {
            background: var(--green-50); border-radius: 20px;
            padding: 48px 40px; text-align: center;
            border: 1px solid var(--green-100);
        }
        .ohh-how-visual-num {
            font-family: 'Playfair Display', serif;
            font-size: 80px; font-weight: 500; color: var(--green-600);
            line-height: 1; margin-bottom: 12px; opacity: 0.3;
        }
        .ohh-how-visual-text {
            font-size: 14px; color: var(--green-600); font-weight: 500;
            letter-spacing: 0.05em; text-transform: uppercase;
        }

        /* ── Gallery ── */
        .ohh-gallery { padding: 96px 0; background: var(--cream); }
        .ohh-gallery-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            grid-template-rows: auto;
            gap: 16px;
        }
        .ohh-gallery-item {
            border-radius: 14px; overflow: hidden;
            position: relative; cursor: pointer;
            background: #E5E5E0;
        }
        .ohh-gallery-item img {
            width: 100%; height: 100%;
            object-fit: cover; display: block;
            transition: transform 0.4s;
        }
        .ohh-gallery-item:hover img { transform: scale(1.04); }
        .ohh-gallery-item.tall { grid-row: span 2; }
        .ohh-gallery-caption {
            position: absolute; bottom: 0; left: 0; right: 0;
            background: linear-gradient(transparent, rgba(28,28,26,0.75));
            padding: 24px 16px 14px;
            color: #fff; font-size: 13px; font-weight: 500;
            opacity: 0; transition: opacity 0.2s;
        }
        .ohh-gallery-item:hover .ohh-gallery-caption { opacity: 1; }

        /* ── CTA ── */
        .ohh-cta {
            padding: 96px 0; background: var(--green-800);
            position: relative; overflow: hidden; text-align: center;
        }
        .ohh-cta::before {
            content: '';
            position: absolute; top: -100px; left: 50%;
            transform: translateX(-50%);
            width: 600px; height: 600px; border-radius: 50%;
            background: rgba(255,255,255,0.03);
        }
        .ohh-cta h2 {
            font-family: 'Playfair Display', serif;
            font-size: clamp(32px, 4vw, 52px);
            font-weight: 500; color: #fff; margin-bottom: 16px; line-height: 1.2;
        }
        .ohh-cta p { font-size: 17px; color: rgba(255,255,255,0.65); margin-bottom: 40px; font-weight: 300; }
        .ohh-btn-white {
            background: #fff; color: var(--green-800);
            padding: 14px 36px; border-radius: 8px;
            font-size: 15px; font-weight: 500;
            text-decoration: none; display: inline-block;
            transition: opacity 0.2s;
        }
        .ohh-btn-white:hover { opacity: 0.9; color: var(--green-800); }

        /* ── Contact ── */
        .ohh-contact { padding: 96px 0; background: #fff; }
        .ohh-contact-info { padding-right: 40px; }
        .ohh-contact-info h3 {
            font-family: 'Playfair Display', serif;
            font-size: 28px; font-weight: 500; margin-bottom: 24px; color: var(--ink);
        }
        .ohh-contact-row {
            display: flex; align-items: flex-start; gap: 14px;
            margin-bottom: 20px;
        }
        .ohh-contact-icon {
            width: 40px; height: 40px; border-radius: 10px;
            background: var(--green-50); flex-shrink: 0;
            display: flex; align-items: center; justify-content: center;
            font-size: 18px; color: var(--green-600);
        }
        .ohh-contact-detail { font-size: 14px; color: var(--muted); line-height: 1.6; }
        .ohh-contact-detail a { color: var(--green-600); text-decoration: none; }
        .ohh-contact-detail strong { display: block; font-size: 13px; font-weight: 500; color: var(--ink); margin-bottom: 2px; }
        .ohh-form-card {
            background: var(--cream); border-radius: 20px;
            padding: 40px; border: 1px solid #EAE9E3;
        }
        .ohh-form-card .form-control, .ohh-form-card .form-select {
            border: 1px solid #E0E0D8; border-radius: 10px;
            padding: 12px 16px; font-size: 15px; background: #fff;
            font-family: 'DM Sans', sans-serif;
        }
        .ohh-form-card .form-control:focus {
            border-color: var(--green-600);
            box-shadow: 0 0 0 3px rgba(15,110,86,0.1);
            outline: none;
        }
        .ohh-form-card label {
            font-size: 13px; font-weight: 500; color: var(--ink);
            margin-bottom: 6px; display: block;
        }
        .ohh-form-card .form-group { margin-bottom: 16px; }
    </style>