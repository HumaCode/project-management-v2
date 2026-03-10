  <div class="modal fade modal-dark" id="logoutModal" tabindex="-1">
      <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title"><i class="bi bi-box-arrow-right"></i>Konfirmasi Logout</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
              </div>
              <div class="modal-body">
                  <div class="logout-warn">
                      <i class="bi bi-exclamation-triangle-fill"></i>
                      <p>Apakah Anda yakin ingin <strong>keluar dari sistem</strong>? Semua sesi aktif akan diakhiri
                          dan Anda perlu login kembali untuk mengakses dashboard.</p>
                  </div>
                  <p style="font-size:13px;color:var(--muted);font-family:var(--mono)"><i
                          class="bi bi-person-circle"></i> Logged in as: <span
                          style="color:var(--cyan)">budi@example.com</span></p>
              </div>
              <div class="modal-footer">
                  <button class="btn-cancel" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i>Batalkan</button>
                  <button class="btn-logout"><span><i class="bi bi-box-arrow-right"></i>Ya, Logout
                          Sekarang</span></button>
              </div>
              <div class="modal-drain">
                  <div class="modal-drain-fill" id="drainFill"></div>
              </div>
          </div>
      </div>
  </div>
