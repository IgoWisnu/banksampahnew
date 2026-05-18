<div class="container-fluid pt-4 px-4">
    <div class="row mb-4 align-items-center">
        <div class="col-lg-12">
            <h3 class="fs-4 mb-0 fw-bold text-dark">Superadmin Dashboard</h3>
            <p class="text-muted mb-0">Overview platform Bank Sampah</p>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-sm-6 col-xl-4">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                        <i class="fas fa-building text-success fs-3"></i>
                    </div>
                    <div class="ms-3">
                        <p class="mb-1 text-muted fw-medium">Total Banjar</p>
                        <h4 class="mb-0 fw-bold text-dark"><?= isset($banjarCount) ? $banjarCount : 0 ?></h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-4">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                        <i class="fas fa-user-shield text-primary fs-3"></i>
                    </div>
                    <div class="ms-3">
                        <p class="mb-1 text-muted fw-medium">Total Admin Banjar</p>
                        <h4 class="mb-0 fw-bold text-dark"><?= isset($adminCount) ? $adminCount : 0 ?></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
