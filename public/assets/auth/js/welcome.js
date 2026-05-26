/* ═══════════════════════════════════════════════════════
   CANVAS BACKGROUND
   ═══════════════════════════════════════════════════════ */
(function(){
  var cv=document.getElementById('bgCanvas'),ctx=cv.getContext('2d');
  var W,H,pts=[];
  function init(){
    W=cv.width=window.innerWidth;
    H=cv.height=window.innerHeight;
    pts=[];
    var n=Math.max(24,Math.floor(W*H/25000));
    for(var i=0;i<n;i++)pts.push({
      x:Math.random()*W,y:Math.random()*H,
      vx:(Math.random()-.5)*.32,vy:(Math.random()-.5)*.32,
      r:Math.random()*2.2+.6,p:Math.random()*Math.PI*2,
      c:Math.random()<.6?[0,212,255]:Math.random()<.5?[245,158,11]:[0,229,160]
    });
  }
  function draw(){
    ctx.clearRect(0,0,W,H);
    // Radial gradient center
    var g=ctx.createRadialGradient(W*.5,H*.45,0,W*.5,H*.45,Math.max(W,H)*.65);
    g.addColorStop(0,'rgba(0,212,255,.042)');g.addColorStop(1,'transparent');
    ctx.fillStyle=g;ctx.fillRect(0,0,W,H);
    // Connection lines
    for(var i=0;i<pts.length;i++){
      var a=pts[i];
      for(var j=i+1;j<pts.length;j++){
        var b=pts[j],dx=a.x-b.x,dy=a.y-b.y,d=Math.sqrt(dx*dx+dy*dy);
        if(d<145){
          ctx.beginPath();ctx.moveTo(a.x,a.y);ctx.lineTo(b.x,b.y);
          ctx.strokeStyle='rgba(0,212,255,'+(1-d/145)*.11+')';
          ctx.lineWidth=.7;ctx.stroke();
        }
      }
    }
    // Dots
    pts.forEach(function(p){
      p.x+=p.vx;p.y+=p.vy;p.p+=.014;
      if(p.x<-12)p.x=W+12;if(p.x>W+12)p.x=-12;
      if(p.y<-12)p.y=H+12;if(p.y>H+12)p.y=-12;
      var a=.22+Math.sin(p.p)*.18,r=p.r*(.85+Math.sin(p.p)*.15);
      ctx.beginPath();ctx.arc(p.x,p.y,r,0,Math.PI*2);
      ctx.fillStyle='rgba('+p.c[0]+','+p.c[1]+','+p.c[2]+','+a+')';ctx.fill();
    });
    requestAnimationFrame(draw);
  }
  window.addEventListener('resize',function(){init();},{passive:true});
  init();draw();
})();

/* ═══════════════════════════════════════════════════════
   CURSOR
   ═══════════════════════════════════════════════════════ */
(function(){
  var dot=document.getElementById('cd'),ring=document.getElementById('cr');
  if(!dot||!ring)return;
  var mx=0,my=0,rx=0,ry=0;
  document.addEventListener('mousemove',function(e){mx=e.clientX;my=e.clientY;dot.style.left=mx+'px';dot.style.top=my+'px';},{passive:true});
  function trackRing(){rx+=(mx-rx)*.18;ry+=(my-ry)*.18;ring.style.left=rx+'px';ring.style.top=ry+'px';requestAnimationFrame(trackRing);}
  trackRing();
  document.querySelectorAll('a,button,.feat-card,.tcard,.pcard,.step-card').forEach(function(el){
    el.addEventListener('mouseenter',function(){ring.classList.add('hov')});
    el.addEventListener('mouseleave',function(){ring.classList.remove('hov')});
  });
})();

/* ═══════════════════════════════════════════════════════
   MOCK CHART BARS
   ═══════════════════════════════════════════════════════ */
(function(){
  var chart=document.getElementById('mkChart');
  if(!chart)return;
  var colors=['#0099cc','#00d4ff','#0099cc','#f59e0b','#00d4ff','#00e5a0','#a78bfa','#0099cc','#00d4ff','#00e5a0'];
  var heights=[40,60,45,80,55,70,35,90,65,75];
  heights.forEach(function(h,i){
    var b=document.createElement('div');
    b.className='mcc';
    b.style.cssText='flex:1;border-radius:3px 3px 0 0;background:'+colors[i]+';height:'+h+'%;opacity:.75;transition:height .4s ease';
    chart.appendChild(b);
    setTimeout(function(){b.style.opacity='1'},300+i*80);
  });
})();

/* ═══════════════════════════════════════════════════════
   NAVBAR
   ═══════════════════════════════════════════════════════ */
var navbar=document.getElementById('navbar');
var navHam=document.getElementById('navHam');
var hamIco=document.getElementById('hamIco');
var navDrawer=document.getElementById('navDrawer');
var drawerOpen=false;

if (navbar) {
  window.addEventListener('scroll',function(){
    navbar.classList.toggle('scrolled',window.scrollY>30);
  },{passive:true});
}

if (navHam && hamIco && navDrawer) {
  navHam.addEventListener('click',function(){
    drawerOpen=!drawerOpen;
    navDrawer.classList.toggle('open',drawerOpen);
    hamIco.className=drawerOpen?'bi bi-x-lg':'bi bi-list';
  });
}

/* ═══════════════════════════════════════════════════════
   SMOOTH SCROLL & ACTIVE LINK
   ═══════════════════════════════════════════════════════ */
function smoothTo(target){
  var el=document.querySelector(target);
  if(!el)return;
  var offset=document.getElementById('navbar').offsetHeight+8;
  var top=el.getBoundingClientRect().top+window.scrollY-offset;
  window.scrollTo({top:top,behavior:'smooth'});
  // close drawer
  if(drawerOpen && navDrawer && hamIco){drawerOpen=false;navDrawer.classList.remove('open');hamIco.className='bi bi-list';}
}

// Nav link clicks
document.querySelectorAll('[data-sec]').forEach(function(link){
  link.addEventListener('click',function(e){
    e.preventDefault();
    smoothTo('#'+this.dataset.sec);
  });
});

// Active link on scroll
var sections=document.querySelectorAll('section[id]');
function onScroll(){
  var nav = document.getElementById('navbar');
  if (!nav) return;
  var scrollY=window.scrollY+nav.offsetHeight+60;
  var current='beranda';
  sections.forEach(function(s){
    if(s.offsetTop<=scrollY) current=s.id;
  });
  document.querySelectorAll('[data-sec]').forEach(function(a){
    a.classList.toggle('active',a.dataset.sec===current);
  });
}
window.addEventListener('scroll',onScroll,{passive:true});

/* ═══════════════════════════════════════════════════════
   SCROLL REVEAL
   ═══════════════════════════════════════════════════════ */
(function(){
  var obs=new IntersectionObserver(function(entries){
    entries.forEach(function(e){if(e.isIntersecting){e.target.classList.add('up');}});
  },{threshold:.12,rootMargin:'0px 0px -40px 0px'});
  document.querySelectorAll('.reveal,.reveal-l,.reveal-r,.reveal-s').forEach(function(el){obs.observe(el);});
})();

/* ═══════════════════════════════════════════════════════
   COUNTER ANIMATION
   ═══════════════════════════════════════════════════════ */
(function(){
  var obs=new IntersectionObserver(function(entries){
    entries.forEach(function(e){
      if(!e.isIntersecting)return;
      var el=e.target,target=parseInt(el.getAttribute('data-count')),start=0,dur=1600;
      var t0=null;
      function step(ts){
        if(!t0)t0=ts;
        var progress=Math.min((ts-t0)/dur,1);
        var ease=1-Math.pow(1-progress,3);
        el.textContent=Math.round(start+(target-start)*ease)+(target>=99&&target<100?'':'')+(target>100?'+':'')+(target===99?'%':'');
        if(progress<1)requestAnimationFrame(step);
        else el.textContent=target+(target>100?'+':'')+(target===99?'%':'');
      }
      requestAnimationFrame(step);
      obs.unobserve(el);
    });
  },{threshold:.5});
  document.querySelectorAll('[data-count]').forEach(function(el){obs.observe(el);});
})();

/* ═══════════════════════════════════════════════════════
   FAB
   ═══════════════════════════════════════════════════════ */
(function(){
  var fab=document.getElementById('fab');
  var fabRing=document.getElementById('fabRing');
  var fabFlash=document.getElementById('fabFlash');
  var circumference=163;
  var scrolling=false;

  if (fab && fabRing) {
    window.addEventListener('scroll',function(){
      if(!scrolling){
        requestAnimationFrame(function(){
          var show=window.scrollY>320;
          fab.classList.toggle('on',show);
          // Update progress ring
          var max=document.documentElement.scrollHeight-window.innerHeight;
          var progress=max>0?window.scrollY/max:0;
          fabRing.style.strokeDashoffset=circumference-(circumference*progress);
          scrolling=false;
        });
        scrolling=true;
      }
    },{passive:true});

    fab.addEventListener('click',function(){
      window.scrollTo({top:0,behavior:'smooth'});
    });
  }

  /* ── Smooth scroll for nav links ── */
  document.querySelectorAll('a[href^="#"]').forEach(function(a){
    a.addEventListener('click',function(e){
      var id=this.getAttribute('href');
      var el=document.querySelector(id);
      if(el){e.preventDefault();el.scrollIntoView({behavior:'smooth',block:'start'});}
    });
  });

})();
