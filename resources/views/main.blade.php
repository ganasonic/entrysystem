<div class="mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <!--
                <div class="card-header bg-primary text-white">
                    メインメニュー
                </div>
-->
                <div class="card-body">
                    <div class="row row-cols-1 row-cols-md-3 g-4">
                        <div class="col">
                            <div class="d-grid">
                                <a href="{{ route('tournaments.select') }}" class="btn btn-lg btn-outline-primary rounded-lg p-4 shadow">
                                    <i class="fas fa-trophy fa-3x mb-2"></i>
                                    <h5 class="mt-2">大会選択</h5>
                                    <p class="mb-0 text-muted">エントリーする大会を選びます</p>
                                </a>
                            </div>
                        </div>
                        <div class="col">
                            <div class="d-grid">
                                <a href="#" class="btn btn-lg btn-outline-success rounded-lg p-4 shadow">
                                    <i class="fas fa-cog fa-3x mb-2"></i>
                                    <h5 class="mt-2">大会設定</h5>
                                    <p class="mb-0 text-muted">大会情報の管理を行います</p>
                                </a>
                            </div>
                        </div>
                        <div class="col">
                            <div class="d-grid">
                                <a href="#" class="btn btn-lg btn-outline-info rounded-lg p-4 shadow">
                                    <i class="fas fa-check-circle fa-3x mb-2"></i>
                                    <h5 class="mt-2">エントリー確認</h5>
                                    <p class="mb-0 text-muted">エントリー状況を確認します</p>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-1ycn6IcaQQmRKAfxVeQxX5Nj/G9IbYVL+3uN5ucfJEVNExtmozvAFw8jxkpZZpiktdvnKGK5Y19mtuY6gIPLkQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<style>
    .btn-outline-primary:hover {
        background-color: #007bff;
        color: white;
    }

    .btn-outline-success:hover {
        background-color: #28a745;
        color: white;
    }

    .btn-outline-info:hover {
        background-color: #17a2b8;
        color: white;
    }
</style>
