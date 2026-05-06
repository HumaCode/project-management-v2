<div class="row g-3 mb-24" data-aos="fade-up" data-aos-delay="50">
    <div class="col-6 col-md-3">
        <div class="stat-mini">
            <div class="stat-mini-ico" style="background:rgba(0,114,198,.12);border:1px solid rgba(0,114,198,.22);color:var(--blue2)"><i class="bi bi-kanban"></i></div>
            <div>
                <div class="stat-mini-num" id="cnt-all">{{ $total_projects }}</div>
                <div class="stat-mini-lbl">Total Project</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-mini">
            <div class="stat-mini-ico" style="background:rgba(0,200,255,.1);border:1px solid rgba(0,200,255,.18);color:var(--cyan)"><i class="bi bi-arrow-repeat"></i></div>
            <div>
                <div class="stat-mini-num" id="cnt-prog">{{ $in_progress }}</div>
                <div class="stat-mini-lbl">In Progress</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-mini">
            <div class="stat-mini-ico" style="background:rgba(0,229,160,.1);border:1px solid rgba(0,229,160,.18);color:var(--ok)"><i class="bi bi-check2-circle"></i></div>
            <div>
                <div class="stat-mini-num" id="cnt-done">{{ $done }}</div>
                <div class="stat-mini-lbl">Done</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-mini">
            <div class="stat-mini-ico" style="background:rgba(120,130,150,.1);border:1px solid rgba(120,130,150,.18);color:#9aabb8"><i class="bi bi-hourglass-split"></i></div>
            <div>
                <div class="stat-mini-num" id="cnt-todo">{{ $to_do }}</div>
                <div class="stat-mini-lbl">To Do</div>
            </div>
        </div>
    </div>
</div>
