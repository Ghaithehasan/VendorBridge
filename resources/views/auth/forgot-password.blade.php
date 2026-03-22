<x-guest-layout>
<style>
@import url('https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Mono:wght@400;500&family=DM+Sans:wght@400;500;600&display=swap');
*{box-sizing:border-box;margin:0;padding:0}
@keyframes float1{0%,100%{transform:translate(0,0) rotate(0deg)}50%{transform:translate(20px,-30px) rotate(180deg)}}
@keyframes float2{0%,100%{transform:translate(0,0) rotate(0deg)}50%{transform:translate(-25px,20px) rotate(-120deg)}}
@keyframes float3{0%,100%{transform:translate(0,0)}50%{transform:translate(15px,25px)}}
@keyframes pulse{0%,100%{opacity:0.15}50%{opacity:0.5}}
@keyframes dash{0%{stroke-dashoffset:300}100%{stroke-dashoffset:0}}
@keyframes fadeInRight{from{opacity:0;transform:translateX(30px)}to{opacity:1;transform:translateX(0)}}
@keyframes slideInLeft{from{opacity:0;transform:translateX(-40px)}to{opacity:1;transform:translateX(0)}}
@keyframes scanline{0%{top:-2%}100%{top:102%}}
@keyframes blink{0%,100%{opacity:1}50%{opacity:0}}
@keyframes shimmer{0%{left:-100%}100%{left:200%}}

.login-shell{display:flex;min-height:100vh;width:100%}
.left{width:58%;background:#080d1a;position:relative;overflow:hidden;animation:slideInLeft 0.9s cubic-bezier(0.16,1,0.3,1) both}
.grid-bg{position:absolute;inset:0;background-image:linear-gradient(rgba(56,189,248,0.03) 1px,transparent 1px),linear-gradient(90deg,rgba(56,189,248,0.03) 1px,transparent 1px);background-size:48px 48px}
.glow{position:absolute;width:500px;height:500px;border-radius:50%;background:radial-gradient(circle,rgba(56,189,248,0.06) 0%,transparent 70%);top:-100px;left:-100px;pointer-events:none}
.glow2{position:absolute;width:400px;height:400px;border-radius:50%;background:radial-gradient(circle,rgba(99,102,241,0.05) 0%,transparent 70%);bottom:-50px;right:-50px;pointer-events:none}
.scanline{position:absolute;left:0;right:0;height:1px;background:linear-gradient(90deg,transparent 0%,rgba(56,189,248,0.2) 30%,rgba(56,189,248,0.4) 50%,rgba(56,189,248,0.2) 70%,transparent 100%);animation:scanline 5s linear infinite}
.hex{position:absolute;opacity:0.07}
.hex1{top:6%;left:8%;animation:float1 10s ease-in-out infinite}
.hex2{top:42%;right:6%;animation:float2 13s ease-in-out infinite}
.hex3{bottom:12%;left:22%;animation:float3 11s ease-in-out infinite}
.network{position:absolute;inset:0;width:100%;height:100%}
.lc{position:relative;z-index:2;padding:40px 44px;height:100vh;display:flex;flex-direction:column;justify-content:space-between}
.brand{display:flex;align-items:center;gap:10px}
.brand-dot{width:7px;height:7px;border-radius:50%;background:#38bdf8;flex-shrink:0;box-shadow:0 0 8px rgba(56,189,248,0.6)}
.brand-name{font-family:'DM Mono',monospace;font-size:12px;font-weight:500;color:#38bdf8;letter-spacing:0.18em}
.hero{flex:1;display:flex;flex-direction:column;justify-content:center;padding:24px 0}
.tag{display:inline-flex;align-items:center;gap:7px;background:rgba(56,189,248,0.08);border:0.5px solid rgba(56,189,248,0.2);border-radius:20px;padding:5px 14px;margin-bottom:24px;width:fit-content}
.tag-dot{width:5px;height:5px;border-radius:50%;background:#38bdf8;animation:blink 1.8s infinite;flex-shrink:0}
.tag-text{font-family:'DM Mono',monospace;font-size:10px;color:#38bdf8;letter-spacing:0.1em}
.hero-title{font-family:'DM Serif Display',serif;font-size:44px;line-height:1.1;color:#f0f9ff;margin-bottom:18px;letter-spacing:-0.5px}
.hero-title em{font-style:italic;color:#38bdf8}
.hero-sub{font-family:'DM Sans',sans-serif;font-size:13px;color:#4a5568;line-height:1.7;max-width:320px}
.stats{display:flex;gap:10px}
.stat{background:rgba(255,255,255,0.03);border:0.5px solid rgba(255,255,255,0.07);border-radius:10px;padding:14px 16px;flex:1;position:relative;overflow:hidden}
.stat::after{content:'';position:absolute;top:0;left:0;right:0;height:1px;background:linear-gradient(90deg,transparent,rgba(56,189,248,0.3),transparent)}
.stat-value{font-family:'DM Mono',monospace;font-size:24px;font-weight:500;color:#e2e8f0}
.stat-label{font-family:'DM Mono',monospace;font-size:9px;color:#334155;margin-top:4px;letter-spacing:0.08em}
.right{width:42%;background:#fafafa;display:flex;flex-direction:column;justify-content:center;padding:44px 40px;animation:fadeInRight 0.9s cubic-bezier(0.16,1,0.3,1) 0.15s both;border-left:1px solid #e8edf2}
.form-eyebrow{font-family:'DM Mono',monospace;font-size:10px;font-weight:500;color:#9ca3af;letter-spacing:0.2em;margin-bottom:20px}
.form-title{font-family:'DM Serif Display',serif;font-size:28px;color:#0f172a;margin-bottom:6px;letter-spacing:-0.3px;line-height:1.2}
.form-sub{font-family:'DM Sans',sans-serif;font-size:12px;color:#6b7280;margin-bottom:28px;line-height:1.6}
.form-field{margin-bottom:16px}
.submit-btn{width:100%;padding:11px;background:#080d1a;color:#f0f9ff;border:none;border-radius:8px;font-family:'DM Sans',sans-serif;font-size:13px;font-weight:600;cursor:pointer;letter-spacing:0.04em;position:relative;overflow:hidden;transition:transform 0.15s;margin-top:8px}
.submit-btn:hover{transform:translateY(-1px)}
.submit-btn::after{content:'';position:absolute;top:0;width:40%;height:100%;background:linear-gradient(90deg,transparent,rgba(255,255,255,0.06),transparent);animation:shimmer 2.5s infinite}
.back-link{display:block;text-align:center;margin-top:18px;font-family:'DM Sans',sans-serif;font-size:12px;color:#6b7280;text-decoration:none;transition:color 0.15s}
.back-link:hover{color:#0f172a}
.divider{display:flex;align-items:center;gap:10px;margin:18px 0}
.divider-line{flex:1;height:1px;background:#f0f0f0}
.divider-text{font-family:'DM Mono',monospace;font-size:10px;color:#d1d5db}
</style>

<div class="login-shell">
  <div class="left">
    <div class="grid-bg"></div>
    <div class="glow"></div>
    <div class="glow2"></div>
    <div class="scanline"></div>

    <svg class="network" viewBox="0 0 600 600" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice">
      <line x1="100" y1="150" x2="280" y2="260" stroke="rgba(56,189,248,0.1)" stroke-width="0.5" stroke-dasharray="300" stroke-dashoffset="300" style="animation:dash 3s ease forwards 0.6s"/>
      <line x1="280" y1="260" x2="460" y2="190" stroke="rgba(56,189,248,0.1)" stroke-width="0.5" stroke-dasharray="300" stroke-dashoffset="300" style="animation:dash 3s ease forwards 1.1s"/>
      <line x1="280" y1="260" x2="200" y2="420" stroke="rgba(56,189,248,0.1)" stroke-width="0.5" stroke-dasharray="300" stroke-dashoffset="300" style="animation:dash 3s ease forwards 1.6s"/>
      <line x1="200" y1="420" x2="420" y2="480" stroke="rgba(56,189,248,0.1)" stroke-width="0.5" stroke-dasharray="300" stroke-dashoffset="300" style="animation:dash 3s ease forwards 2.1s"/>
      <line x1="460" y1="190" x2="420" y2="480" stroke="rgba(99,102,241,0.08)" stroke-width="0.5" stroke-dasharray="300" stroke-dashoffset="300" style="animation:dash 3s ease forwards 2.6s"/>
      <circle cx="100" cy="150" r="3.5" fill="#38bdf8" style="animation:pulse 4s ease-in-out infinite"/>
      <circle cx="280" cy="260" r="6" fill="#38bdf8" style="animation:pulse 4s ease-in-out infinite 0.6s"/>
      <circle cx="460" cy="190" r="3.5" fill="#6366f1" style="animation:pulse 4s ease-in-out infinite 1.2s"/>
      <circle cx="200" cy="420" r="4.5" fill="#38bdf8" style="animation:pulse 4s ease-in-out infinite 1.8s"/>
      <circle cx="420" cy="480" r="3.5" fill="#6366f1" style="animation:pulse 4s ease-in-out infinite 2.4s"/>
    </svg>

    <svg class="hex hex1" width="100" height="100" viewBox="0 0 80 80">
      <polygon points="40,5 72,22 72,58 40,75 8,58 8,22" fill="none" stroke="#38bdf8" stroke-width="0.7"/>
      <polygon points="40,16 62,28 62,52 40,64 18,52 18,28" fill="none" stroke="#38bdf8" stroke-width="0.3"/>
    </svg>
    <svg class="hex hex2" width="72" height="72" viewBox="0 0 60 60">
      <polygon points="30,4 54,17 54,43 30,56 6,43 6,17" fill="none" stroke="#6366f1" stroke-width="0.7"/>
    </svg>
    <svg class="hex hex3" width="56" height="56" viewBox="0 0 50 50">
      <polygon points="25,3 45,14 45,36 25,47 5,36 5,14" fill="none" stroke="#38bdf8" stroke-width="0.5"/>
    </svg>

    <div class="lc">
      <div class="brand">
        <div class="brand-dot"></div>
        <div class="brand-name">PROCUREFLOW</div>
      </div>
      <div class="hero">
        <div class="tag">
          <div class="tag-dot"></div>
          <span class="tag-text">ACCOUNT RECOVERY</span>
        </div>
        <div class="hero-title">
          Reset access<br>with <em>confidence</em>.
        </div>
        <div class="hero-sub">
          We will send a secure link to your registered email so you can choose a new password and return to the procurement dashboard.
        </div>
      </div>
      <div class="stats">
        <div class="stat"><div class="stat-value">24</div><div class="stat-label">PRs PROCESSED</div></div>
        <div class="stat"><div class="stat-value">07</div><div class="stat-label">ACTIVE RFQs</div></div>
        <div class="stat"><div class="stat-value">04</div><div class="stat-label">VENDORS</div></div>
      </div>
    </div>
  </div>

  <div class="right">
    <div class="form-eyebrow">PROCUREMENT SYSTEM</div>
    <div class="form-title">Forgot your<br>password?</div>
    <div class="form-sub">
      Enter the email address associated with your account. If it exists in the system, you will receive a password reset link shortly.
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
      @csrf
      <div class="form-field">
        <x-input-label for="email" :value="__('Email Address')" />
        <x-text-input id="email" class="block mt-1 w-full" type="email" name="email"
          :value="old('email')" required autofocus autocomplete="username"
          style="border:1px solid #e5e7eb;border-radius:8px;padding:9px 12px;font-family:'DM Sans',sans-serif;font-size:13px;background:#fff;width:100%;outline:none;transition:all 0.2s"/>
        <x-input-error :messages="$errors->get('email')" class="mt-2" />
      </div>
      <button type="submit" class="submit-btn">{{ __('Email Password Reset Link') }} →</button>
      <div class="divider">
        <div class="divider-line"></div>
        <span class="divider-text">SECURE ACCESS</span>
        <div class="divider-line"></div>
      </div>
      <a class="back-link" href="{{ route('login') }}">← {{ __('Back to sign in') }}</a>
    </form>
  </div>
</div>
</x-guest-layout>
