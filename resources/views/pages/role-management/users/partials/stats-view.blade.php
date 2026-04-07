<div class="stat-row" data-aos="fade-up" data-aos-delay="40">
    <div class="sc w">
        <div class="sc-ico w"><i class="bi bi-person-plus"></i></div>
        <div>
            <div class="sc-val">{{ $countNewUser }}</div>
            <div class="sc-lbl">Baru</div>
        </div>
    </div>
    <div class="sc c">
        <div class="sc-ico c"><i class="bi bi-people-fill"></i></div>
        <div>
            <div class="sc-val">{{ $countAllUser }}</div>
            <div class="sc-lbl">Total User</div>
        </div>
    </div>
    <div class="sc g">
        <div class="sc-ico g"><i class="bi bi-person-check-fill"></i></div>
        <div>
            <div class="sc-val">{{ $countAllUserActive }}</div>
            <div class="sc-lbl">Aktif</div>
        </div>
    </div>

    <div class="sc r">
        <div class="sc-ico r"><i class="bi bi-person-x-fill"></i></div>
        <div>
            <div class="sc-val">{{ $countAllUserInactive }}</div>
            <div class="sc-lbl">Nonaktif</div>
        </div>
    </div>
</div>
