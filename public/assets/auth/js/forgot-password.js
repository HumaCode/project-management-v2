/* ── Network Canvas ──────────────────────────────────────── */
(function () {
  const canvas = document.getElementById('bg-canvas');
  if(!canvas) return;
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
  const hues=['rgba(0,200,255,','rgba(0,114,198,','rgba(245,158,11,'];
  for(let i=0;i<18;i++){
    const p=document.createElement('div');
    p.className='particle';
    const s=Math.random()*4+2,c=hues[Math.floor(Math.random()*hues.length)];
    p.style.cssText=`width:${s}px;height:${s}px;left:${Math.random()*100}vw;bottom:-10px;background:${c}${.4+Math.random()*.3}));animation-duration:${12+Math.random()*20}s;animation-delay:${Math.random()*-20}s;--drift:${(Math.random()-.5)*120}px;box-shadow:0 0 ${s*3}px ${c}.6));`;
    document.body.appendChild(p);
  }
})();

/* ── Form Interactions ───────────────────────────────────── */
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('formEmail');
    if (form) {
        form.addEventListener('submit', function() {
            const btn = document.getElementById('btnEmail');
            if (btn) {
                btn.classList.add('loading');
                btn.disabled = true;
            }
        });
    }

    const emailInput = document.getElementById('email');
    if (emailInput) {
        emailInput.addEventListener('input', function() {
            this.classList.remove('is-invalid');
            const msg = this.closest('.field-group').querySelector('.field-msg');
            if (msg) msg.style.display = 'none';
        });
    }
});
