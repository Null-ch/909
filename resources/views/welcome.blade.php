<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Красивый газон за один день | Газон-ННов</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome 6 (бесплатные иконки) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Подключение своего CSS при необходимости -->
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .hero-section {
            background: linear-gradient(135deg, #1b4d1b, #2d7a2d);
            color: white;
            padding: 6rem 0;
            margin-bottom: 3rem;
        }
        .hero-section h1 {
            font-size: 3.5rem;
            font-weight: 700;
        }
        .feature-icon {
            font-size: 2.5rem;
            color: #2d7a2d;
            margin-bottom: 1rem;
        }
        .feature-box {
            padding: 2rem 1rem;
            border-radius: 12px;
            transition: box-shadow 0.3s ease;
            height: 100%;
            background-color: #f8f9fa;
        }
        .feature-box:hover {
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
            background-color: #ffffff;
        }
        .btn-primary-custom {
            background-color: #f5a623;
            border: none;
            color: #1b4d1b;
            font-weight: 600;
            padding: 0.8rem 2.5rem;
            border-radius: 30px;
        }
        .btn-primary-custom:hover {
            background-color: #e0991f;
            color: #0d330d;
        }
        .text-green {
            color: #1b4d1b;
        }
        .bg-light-green {
            background-color: #f0f7f0;
        }
        .section-title {
            font-weight: 600;
            margin-bottom: 2.5rem;
            color: #1b4d1b;
        }
        .card-custom {
            border: none;
            border-radius: 16px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            transition: transform 0.2s;
            height: 100%;
        }
        .card-custom:hover {
            transform: translateY(-5px);
        }
    </style>
</head>
<body>

    <!-- HERO СЕКЦИЯ (Заглавный блок) -->
    <section class="hero-section">
        <div class="container text-center">
            <h1 class="display-4 fw-bold mb-4">Красивый газон за один день</h1>
            <p class="lead mb-4">У нас вы сможете купить по выгодным ценам оптом и в розницу <br> семена газонных трав, удобрения для газонов и рулонный газон от производителя</p>
            <a href="#" class="btn btn-primary-custom btn-lg">
                <i class="fas fa-leaf me-2"></i>Смотреть ассортимент
            </a>
        </div>
    </section>

    <!-- ПРЕИМУЩЕСТВА -->
    <div class="container mb-5">
        <div class="row g-4">
            <div class="col-md-3 col-6">
                <div class="feature-box text-center">
                    <i class="fas fa-tags feature-icon"></i>
                    <h5>Низкие цены</h5>
                    <p class="text-muted small">Широкий ассортимент</p>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="feature-box text-center">
                    <i class="fas fa-store-alt feature-icon"></i>
                    <h5>Сеть представительств</h5>
                    <p class="text-muted small">По всей области</p>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="feature-box text-center">
                    <i class="fas fa-certificate feature-icon"></i>
                    <h5>Сертификация</h5>
                    <p class="text-muted small">Весь товар проверен</p>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="feature-box text-center">
                    <i class="fas fa-truck feature-icon"></i>
                    <h5>Оперативная доставка</h5>
                    <p class="text-muted small">Быстрая отгрузка</p>
                </div>
            </div>
        </div>
    </div>

    <!-- БЛОК О ГАЗОНАХ -->
    <div class="container mb-5">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <h2 class="section-title">Семена газона в Нижнем Новгороде</h2>
                <p class="text-muted" style="font-size: 1.1rem;">
                    Качественный газон придаст любому участку ухоженный презентабельный вид, скроет рельефные недостатки, придаст ему завершённый вид. Правильно подобранный травяной покров и надлежащий уход за газоном будет долгие годы радовать своим зелёным ковром.
                </p>
                <p class="text-muted" style="font-size: 1.1rem;">
                    Газонная трава не только придаёт участку эстетичность, но и избавляет от сорняков, насыщает воздух кислородом, очищают почву и воздух от вредных организмов и веществ, задерживают пыль.
                </p>
            </div>
            <div class="col-lg-6">
                <div class="bg-light-green p-4 rounded-4">
                    <h4 class="text-green">Преимущества наших травосмесей</h4>
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Быстро укореняются в почве</li>
                        <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Устойчивы к засухе и перепадам температур</li>
                        <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Не подвержены вредителям</li>
                        <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Замедленный рост после скашивания</li>
                        <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Долго сохраняют сочность цвета</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- КАРТОЧКИ С СЕМЕНАМИ (пример трав) -->
    <div class="container mb-5">
        <h2 class="section-title text-center">Популярные виды трав</h2>
        <div class="row g-4">
            <div class="col-md-3">
                <div class="card card-custom p-3">
                    <div class="text-center">
                        <i class="fas fa-seedling" style="font-size: 3rem; color: #2d7a2d;"></i>
                    </div>
                    <div class="card-body text-center">
                        <h5 class="card-title">Райграс</h5>
                        <p class="card-text small">Мощная корневая система, быстро растет</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-custom p-3">
                    <div class="text-center">
                        <i class="fas fa-leaf" style="font-size: 3rem; color: #2d7a2d;"></i>
                    </div>
                    <div class="card-body text-center">
                        <h5 class="card-title">Овсяница луговая</h5>
                        <p class="card-text small">Неприхотлива к любому типу грунта</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-custom p-3">
                    <div class="text-center">
                        <i class="fas fa-tree" style="font-size: 3rem; color: #2d7a2d;"></i>
                    </div>
                    <div class="card-body text-center">
                        <h5 class="card-title">Мятлик луговой</h5>
                        <p class="card-text small">Быстрорастущий, неприхотлив в уходе</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-custom p-3">
                    <div class="text-center">
                        <i class="fas fa-feather-alt" style="font-size: 3rem; color: #2d7a2d;"></i>
                    </div>
                    <div class="card-body text-center">
                        <h5 class="card-title">Житняк</h5>
                        <p class="card-text small">Эффективно вытесняет сорняки</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ПРИЗЫВ К ДЕЙСТВИЮ (CTA) -->
    <div class="bg-light-green py-5">
        <div class="container text-center">
            <h3 class="text-green mb-3">Создайте свой идеальный газон уже сегодня!</h3>
            <p class="text-muted mb-4">Откройте для себя нашу продукцию и получите бесплатную консультацию.</p>
            <a href="#" class="btn btn-primary-custom btn-lg">
                <i class="fas fa-shopping-cart me-2"></i>Перейти в каталог
            </a>
        </div>
    </div>

    <!-- ПОДВАЛ (Footer) -->
    <footer class="bg-dark text-white py-4 mt-5">
        <div class="container text-center">
            <p class="mb-0 small">© 2026 Газон-ННов. Семена газонных трав в Нижнем Новгороде.</p>
        </div>
    </footer>

    <!-- Bootstrap JS (необязательно, но для некоторых компонентов нужен) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>