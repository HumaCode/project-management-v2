/* ── Network Canvas ──────────────────────────────────────── */
(function () {
  const canvas = document.getElementById('bg-canvas');
  const ctx = canvas.getContext('2d');
  let W, H, nodes = [];
  const COLORS = ['rgba(0,200,255,','rgba(0,114,198,','rgba(0,229,160,'];

  function resize() {
    W = canvas.width  = window.innerWidth;
    H = canvas.height = window.innerHeight;
    nodes = [];
    const n = Math.max(28, Math.floor((W * H) / 22000));
    for (let i = 0; i < n; i++) {
      nodes.push({
        x: Math.random()*W, y: Math.random()*H,
        vx: (Math.random()-.5)*.45, vy: (Math.random()-.5)*.45,
        r: Math.random()*2+1,
        c: COLORS[Math.floor(Math.random()*COLORS.length)],
        p: Math.random()*Math.PI*2,
      });
    }
  }

  function draw() {
    ctx.clearRect(0,0,W,H);
    nodes.forEach(n => {
      n.x+=n.vx; n.y+=n.vy; n.p+=0.018;
      if(n.x<-20)n.x=W+20; if(n.x>W+20)n.x=-20;
      if(n.y<-20)n.y=H+20; if(n.y>H+20)n.y=-20;
    });
    for(let i=0;i<nodes.length;i++) {
      for(let j=i+1;j<nodes.length;j++) {
        const a=nodes[i],b=nodes[j];
        const dx=a.x-b.x,dy=a.y-b.y,d=Math.sqrt(dx*dx+dy*dy);
        if(d<160){
          ctx.beginPath();ctx.moveTo(a.x,a.y);ctx.lineTo(b.x,b.y);
          ctx.strokeStyle=`rgba(0,160,220,${(1-d/160)*.28})`;
          ctx.lineWidth=.8;ctx.stroke();
        }
      }
    }
    nodes.forEach(n=>{
      const g=.5+Math.sin(n.p)*.35;
      ctx.beginPath();
      ctx.arc(n.x,n.y,n.r*(.9+Math.sin(n.p)*.15),0,Math.PI*2);
      ctx.fillStyle=n.c+g+')';ctx.fill();
      if(n.r>2){
        ctx.beginPath();ctx.arc(n.x,n.y,n.r*3.5,0,Math.PI*2);
        ctx.fillStyle=n.c+(g*.08)+')';ctx.fill();
      }
    });
    requestAnimationFrame(draw);
  }
  window.addEventListener('resize',resize,{passive:true});
  resize(); draw();
})();

/* ── Floating Particles ──────────────────────────────────── */
(function(){
  const hues=['rgba(0,200,255,','rgba(0,114,198,','rgba(0,229,160,'];
  for(let i=0;i<18;i++){
    const p=document.createElement('div');
    p.className='particle';
    const s=Math.random()*4+2,c=hues[Math.floor(Math.random()*hues.length)];
    p.style.cssText=`width:${s}px;height:${s}px;left:${Math.random()*100}vw;bottom:-10px;background:${c}${.4+Math.random()*.3}));animation-duration:${12+Math.random()*20}s;animation-delay:${Math.random()*-20}s;--drift:${(Math.random()-.5)*120}px;box-shadow:0 0 ${s*3}px ${c}.6));`;
    document.body.appendChild(p);
  }
})();

/* ── Step Navigation ─────────────────────────────────────── */
const steps = ['stepEmail','stepOtp','stepNewPass','stepDone'];
let currentStep = 1;

function goTo(n) {
  // hide current
  document.getElementById(steps[currentStep-1]).classList.remove('active');

  // update progress dots
  document.querySelectorAll('.pdot').forEach((d,i) => {
    d.classList.remove('active','done');
    if (i < n-1) d.classList.add('done');
    else if (i === n-1) d.classList.add('active');
  });

  // update flow on left panel
  ['fl1','fl2','fl3'].forEach((id,i) => {
    const el = document.getElementById(id);
    const dot = document.getElementById('fd'+(i+1));
    el.classList.remove('active','done','pending');
    if (i < n-1) {
      el.classList.add('done');
      dot.innerHTML = '<i class="bi bi-check-lg" style="font-size:12px"></i>';
    } else if (i === n-1) {
      el.classList.add('active');
      dot.textContent = i+1;
    } else {
      el.classList.add('pending');
      dot.textContent = i+1;
    }
  });

  currentStep = n;
  const panel = document.getElementById(steps[n-1]);
  panel.classList.add('active');

  // scroll form area to top
  document.querySelector('.form-scroll-area').scrollTop = 0;

  // hide sys-info on success
  document.getElementById('sysInfo').style.display = n === 4 ? 'none' : 'block';
}

/* ── Step 1: Email ───────────────────────────────────────── */
const emailInput = document.getElementById('email');
const emailMsg   = document.getElementById('emailMsg');

document.getElementById('formEmail').addEventListener('submit', function(e) {
  e.preventDefault();
  const v = emailInput.value.trim();

  if (!v) {
    setMsg(emailMsg, 'Email tidak boleh kosong.', 'error'); return;
  }
  if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v)) {
    setMsg(emailMsg, 'Format email tidak valid.', 'error'); return;
  }

  const btn = document.getElementById('btnEmail');
  btn.classList.add('loading'); btn.disabled = true;

  // Simulate Laravel Request
  const url = this.getAttribute('data-url');
  const token = document.querySelector('input[name="_token"]').value;

  fetch(url, {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': token,
        'Accept': 'application/json'
    },
    body: JSON.stringify({ email: v })
  })
  .then(response => response.json())
  .then(data => {
      btn.classList.remove('loading'); btn.disabled = false;
      if (data.status) {
          // set subtitle OTP
          document.getElementById('otpSubtitle').innerHTML =
            `Email instruksi pemulihan telah dikirim ke <strong style="color:var(--cyan)">${v}</strong>`;
          // Note: The HTML template has OTP logic which isn't standard Laravel forgot password.
          // Laravel standard just sends an email.
          // I will keep the original logic for now as requested "tampilkan sesuai contoh".
          // But I'll modify it to show success message instead of going to Step 2 if it's just standard Laravel.
          // Actually, let's just stick to the HTML example flow for now.
          goTo(4); // In reality, Laravel just shows a success message.
      } else {
          setMsg(emailMsg, data.email || 'Terjadi kesalahan.', 'error');
      }
  })
  .catch(error => {
      btn.classList.remove('loading'); btn.disabled = false;
      setMsg(emailMsg, 'Terjadi kesalahan jaringan.', 'error');
  });
});

/* ── OTP Logic (Keeping as per template but it might not be used with standard Laravel) ── */
const otpIds   = ['o1','o2','o3','o4','o5','o6'];
const DEMO_OTP = '123456'; // demo: kode benar
let countdownTimer;

otpIds.forEach((id, idx) => {
  const inp = document.getElementById(id);
  if(!inp) return;

  inp.addEventListener('input', function() {
    const val = this.value.replace(/\D/g,'');
    this.value = val.slice(0,1);
    this.classList.toggle('filled', !!this.value);

    if (val && idx < 5) {
      document.getElementById(otpIds[idx+1]).focus();
    }
    clearOtpError();
  });

  inp.addEventListener('keydown', function(e) {
    if (e.key === 'Backspace' && !this.value && idx > 0) {
      document.getElementById(otpIds[idx-1]).focus();
    }
    if (e.key === 'ArrowLeft' && idx > 0) {
      e.preventDefault();
      document.getElementById(otpIds[idx-1]).focus();
    }
    if (e.key === 'ArrowRight' && idx < 5) {
      e.preventDefault();
      document.getElementById(otpIds[idx+1]).focus();
    }
  });

  // paste support
  inp.addEventListener('paste', function(e) {
    e.preventDefault();
    const pasted = (e.clipboardData || window.clipboardData)
      .getData('text').replace(/\D/g,'').slice(0,6);
    pasted.split('').forEach((ch, i) => {
      const el = document.getElementById(otpIds[i]);
      if (el) { el.value = ch; el.classList.add('filled'); }
    });
    const last = Math.min(pasted.length, 5);
    document.getElementById(otpIds[last]).focus();
    clearOtpError();
  });
});

function getOtpValue() {
  return otpIds.map(id => document.getElementById(id).value).join('');
}

function clearOtpError() {
  otpIds.forEach(id => {
    const el = document.getElementById(id);
    if(el) el.classList.remove('error-otp');
  });
  setMsg(document.getElementById('otpMsg'), '', '');
}

const formOtp = document.getElementById('formOtp');
if(formOtp){
    formOtp.addEventListener('submit', function(e) {
      e.preventDefault();
      const otp = getOtpValue();

      if (otp.length < 6) {
        setMsg(document.getElementById('otpMsg'), 'Harap isi semua 6 digit kode.', 'error');
        otpIds.forEach(id => {
          if (!document.getElementById(id).value)
            document.getElementById(id).classList.add('error-otp');
        });
        return;
      }

      const btn = document.getElementById('btnOtp');
      btn.classList.add('loading'); btn.disabled = true;

      setTimeout(() => {
        btn.classList.remove('loading'); btn.disabled = false;

        if (otp !== DEMO_OTP) {
          setMsg(document.getElementById('otpMsg'), 'Kode OTP salah. Silakan periksa kembali.', 'error');
          otpIds.forEach(id => document.getElementById(id).classList.add('error-otp'));
          return;
        }
        clearInterval(countdownTimer);
        goTo(3);
      }, 1400);
    });
}

/* ── Countdown & Resend ─────────────────────────────────── */
function startCountdown(seconds = 120) {
  const el   = document.getElementById('countdown');
  const text = document.getElementById('resendText');
  const btn  = document.getElementById('btnResend');
  if(!el) return;

  clearInterval(countdownTimer);
  btn.disabled = true;
  text.style.display = 'inline';
  el.style.display   = 'inline';

  let remaining = seconds;
  el.textContent = fmt(remaining);

  countdownTimer = setInterval(() => {
    remaining--;
    el.textContent = fmt(remaining);
    if (remaining <= 0) {
      clearInterval(countdownTimer);
      text.style.display = 'none';
      el.style.display   = 'none';
      btn.disabled = false;
    }
  }, 1000);
}

function fmt(s) {
  return `${String(Math.floor(s/60)).padStart(2,'0')}:${String(s%60).padStart(2,'0')}`;
}

function startResend() {
  // clear OTP fields
  otpIds.forEach(id => {
    const el = document.getElementById(id);
    if(el){
        el.value = ''; el.classList.remove('filled','error-otp');
    }
  });
  clearOtpError();
  const firstOtp = document.getElementById(otpIds[0]);
  if(firstOtp) firstOtp.focus();
  setMsg(document.getElementById('otpMsg'), 'Kode baru telah dikirim.', 'success');
  startCountdown();
}

/* ── Step 3: New Password ────────────────────────────────── */
const newPassInput    = document.getElementById('newPass');
const confirmNewInput = document.getElementById('confirmNewPass');
const strengthWrap    = document.getElementById('strengthWrap');
const strengthFill    = document.getElementById('strengthFill');
const strengthText    = document.getElementById('strengthText');
const strengthPct     = document.getElementById('strengthPct');

const pwChecks = {
  len:   { el: document.getElementById('chkLen'),   test: v => v.length >= 8 },
  upper: { el: document.getElementById('chkUpper'), test: v => /[A-Z]/.test(v) },
  num:   { el: document.getElementById('chkNum'),   test: v => /[0-9]/.test(v) },
  sym:   { el: document.getElementById('chkSym'),   test: v => /[!@#$%^&*()\-_=+\[\]{}|;:,.<>?]/.test(v) },
};
const levels = [
  { label:'Sangat Lemah', color:'#ff4d6d', pct:15  },
  { label:'Lemah',        color:'#ff7849', pct:32  },
  { label:'Cukup',        color:'#f59e0b', pct:55  },
  { label:'Kuat',         color:'#22d3ee', pct:78  },
  { label:'Sangat Kuat',  color:'#00e5a0', pct:100 },
];

if(newPassInput){
    newPassInput.addEventListener('input', () => {
      const v = newPassInput.value;
      if (!v) { strengthWrap.style.display='none'; resetInput(newPassInput); return; }

      strengthWrap.style.display = 'block';
      let score = 0;
      Object.values(pwChecks).forEach(c => {
        if(!c.el) return;
        const ok = c.test(v);
        c.el.classList.toggle('pass', ok);
        c.el.querySelector('i').className = ok ? 'bi bi-check-circle-fill' : 'bi bi-x-circle';
        if (ok) score++;
      });

      const lvl = levels[score];
      strengthFill.style.width = lvl.pct + '%';
      strengthFill.style.background = `linear-gradient(90deg,${lvl.color}99,${lvl.color})`;
      strengthText.textContent = lvl.label;
      strengthText.style.color = lvl.color;
      strengthPct.textContent  = lvl.pct + '%';

      newPassInput.classList.remove('is-valid','is-invalid');
      if (score >= 3) newPassInput.classList.add('is-valid');
      else if (score < 2) newPassInput.classList.add('is-invalid');

      checkConfirmNew();
    });
}

function checkConfirmNew() {
  if(!confirmNewInput) return;
  const v = confirmNewInput.value;
  const msg = document.getElementById('confirmNewMsg');
  if (!v) { resetInput(confirmNewInput); msg.innerHTML=''; return; }
  if (v === newPassInput.value) {
    confirmNewInput.classList.remove('is-invalid'); confirmNewInput.classList.add('is-valid');
    setMsg(msg, 'Password cocok', 'success');
  } else {
    confirmNewInput.classList.remove('is-valid'); confirmNewInput.classList.add('is-invalid');
    setMsg(msg, 'Password tidak cocok', 'error');
  }
}
if(confirmNewInput) confirmNewInput.addEventListener('input', checkConfirmNew);

// Toggle new password
const toggleNew = document.getElementById('toggleNew');
if(toggleNew){
    toggleNew.addEventListener('click', () => {
      const i = newPassInput; const e = document.getElementById('eyeNew');
      const s = i.type==='password'; i.type=s?'text':'password';
      e.className = s ? 'bi bi-eye-slash' : 'bi bi-eye';
    });
}
const toggleConfirm = document.getElementById('toggleConfirm');
if(toggleConfirm){
    toggleConfirm.addEventListener('click', () => {
      const i = confirmNewInput; const e = document.getElementById('eyeConfirm');
      const s = i.type==='password'; i.type=s?'text':'password';
      e.className = s ? 'bi bi-eye-slash' : 'bi bi-eye';
    });
}

const formNewPass = document.getElementById('formNewPass');
if(formNewPass){
    formNewPass.addEventListener('submit', function(e) {
      e.preventDefault();
      const newPass  = newPassInput.value;
      const confPass = confirmNewInput.value;
      const msg1     = document.getElementById('newPassMsg');
      const msg2     = document.getElementById('confirmNewMsg');

      if (!newPassInput.classList.contains('is-valid')) {
        setMsg(msg1, 'Password belum memenuhi kriteria keamanan.', 'error'); return;
      }
      if (newPass !== confPass) {
        setMsg(msg2, 'Konfirmasi password tidak cocok.', 'error'); return;
      }

      const btn = document.getElementById('btnNewPass');
      btn.classList.add('loading'); btn.disabled = true;

      setTimeout(() => {
        btn.classList.remove('loading'); btn.disabled = false;
        goTo(4);
      }, 1800);
    });
}

/* ── Helpers ─────────────────────────────────────────────── */
function setMsg(el, text, type) {
  if(!el) return;
  const icons = { success:'bi-check-circle', error:'bi-exclamation-circle', info:'bi-info-circle' };
  el.className = `field-msg ${type}`;
  el.innerHTML = text ? `<i class="bi ${icons[type]||''}"></i>${text}` : '';
}
function resetInput(inp) {
  if(!inp) return;
  inp.classList.remove('is-valid','is-invalid');
}

/* ── Email field live validation ─────────────────────────── */
if(emailInput){
    emailInput.addEventListener('blur', () => {
      const v = emailInput.value.trim();
      if (!v) return;
      if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v)) {
        emailInput.classList.add('is-invalid');
        setMsg(emailMsg, 'Format email tidak valid.', 'error');
      } else {
        emailInput.classList.add('is-valid');
        setMsg(emailMsg, 'Format email valid.', 'success');
      }
    });
    emailInput.addEventListener('input', () => {
      emailInput.classList.remove('is-valid','is-invalid');
      emailMsg.innerHTML = '';
    });
}
