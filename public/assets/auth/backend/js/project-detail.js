/* AOS Initialization */
if (typeof AOS !== 'undefined') {
    AOS.init({ once: true, easing: 'ease-out-cubic', duration: 500, offset: 20 });
}

/* Modal drain utility */
function initDrain(modalId, fillId){
  var m=document.getElementById(modalId);
  if(!m) return;
  m.addEventListener('show.bs.modal',function(){
    var f=document.getElementById(fillId);
    if(!f)return;
    f.classList.remove('go');
    void f.offsetWidth;
    f.classList.add('go');
  });
  m.addEventListener('hidden.bs.modal',function(){
    var f=document.getElementById(fillId);
    if(f)f.classList.remove('go');
  });
}

$(function() {
    /* Count-up animation */
    function countUp(el, target){
      var dur=1200,start=performance.now();
      (function step(now){
          var p=Math.min((now-start)/dur,1),
          ease=1-Math.pow(1-p,3);
          el.textContent=Math.round(ease*target);
          if(p<1)requestAnimationFrame(step);
          else el.textContent=target;
      })(performance.now());
    }

    var io = new IntersectionObserver(function(entries){
        entries.forEach(function(e){
            if(e.isIntersecting){
                var el = e.target.querySelector('[data-count]');
                if(el && !el.dataset.done){
                    el.dataset.done = '1';
                    countUp(el, parseInt(el.dataset.count));
                }
            }
        });
    }, {threshold: .3});

    document.querySelectorAll('.msc').forEach(function(c){
        io.observe(c);
    });

    /* Progress bar animate */
    setTimeout(function(){
      var fill=document.getElementById('progFill');
      if(fill) fill.style.width='72%';
    }, 400);

    /* Tabs */
    $(document).on('click', '.tab-btn', function() {
        var tab = this.dataset.tab;
        $('.tab-btn').removeClass('active');
        $('.tab-pane').removeClass('active');
        $(this).addClass('active');
        $('#tab-'+tab).addClass('active');
    });

    /* Doc search */
    $(document).on('input', '#docSearch', function() {
        var q = $(this).val().toLowerCase();
        $('.doc-tbl tbody tr').each(function() {
            var nm = $(this).find('.doc-nm').text().toLowerCase();
            $(this).toggle(!q || nm.indexOf(q) > -1);
        });
    });

    /* Add note */
    $(document).on('click', '#btnAddNote', function() {
        var ta = $('#noteInput');
        if(!ta.val().trim()) return;
        
        var wrap = $('#noteWrap');
        var card = $('<div>').addClass('note-card').css('animation', 'mup .35s ease both');
        
        card.html(`
            <style>@keyframes mup{from{opacity:0;transform:translateY(8px)}to{opacity:1;transform:translateY(0)}}</style>
            <div class="note-head">
                <div class="note-av">BS</div>
                <div class="note-author">Budi Santoso</div>
                <div class="note-time">Baru saja</div>
            </div>
            <div class="note-body">${ta.val().replace(/</g,'&lt;')}</div>
            <div class="note-actions">
                <button class="note-btn"><i class="bi bi-reply-fill"></i> Balas</button>
                <button class="note-btn"><i class="bi bi-pencil"></i> Edit</button>
                <button class="note-btn" style="color:rgba(255,77,109,.5)"><i class="bi bi-trash3"></i> Hapus</button>
            </div>
        `);
        
        wrap.append(card);
        ta.val('');
        
        // Scroll to bottom
        wrap.animate({ scrollTop: wrap.prop("scrollHeight")}, 500);
    });

    initDrain('logoutModal','drainFill');
    initDrain('deleteModal','drainFill2');
});
