<div class="stat-row" data-aos="fade-up" data-aos-delay="50">
    <div class="sc c">
        <div class="sc-ico c"><i class="bi bi-kanban"></i></div>
        <div>
            <div class="sc-val" id="cnt-all">{{ $total_projects }}</div>
            <div class="sc-lbl">Total Project</div>
        </div>
    </div>
    <div class="sc w">
        <div class="sc-ico w"><i class="bi bi-arrow-repeat"></i></div>
        <div>
            <div class="sc-val" id="cnt-prog">{{ $in_progress }}</div>
            <div class="sc-lbl">In Progress</div>
        </div>
    </div>
    <div class="sc g">
        <div class="sc-ico g"><i class="bi bi-check2-circle"></i></div>
        <div>
            <div class="sc-val" id="cnt-done">{{ $done }}</div>
            <div class="sc-lbl">Done</div>
        </div>
    </div>
    <div class="sc r">
        <div class="sc-ico r"><i class="bi bi-hourglass-split"></i></div>
        <div>
            <div class="sc-val" id="cnt-todo">{{ $to_do }}</div>
            <div class="sc-lbl">To Do</div>
        </div>
    </div>
</div>
