import { useLocaleStore } from '@/store/localeStore'

const ui = {
  // Navigation
  'nav.home':     { ru: 'Главная',               ro: 'Acasă' },
  'nav.products': { ru: 'Товары',                ro: 'Produse' },
  'nav.contact':  { ru: 'Контакты',              ro: 'Contact' },
  'nav.login':    { ru: 'Войти',                 ro: 'Autentificare' },
  'nav.register': { ru: 'Регистрация',            ro: 'Înregistrare' },
  'nav.admin':    { ru: 'Панель администратора', ro: 'Panou admin' },
  'nav.profile':  { ru: 'Личный кабинет',         ro: 'Cabinet personal' },
  'nav.logout':   { ru: 'Выйти',                 ro: 'Ieșire' },

  // Contact page
  'contact.title':    { ru: 'Контакты',                                          ro: 'Contacte' },
  'contact.subtitle': { ru: 'Мы всегда рады помочь. Свяжитесь с нами удобным способом.', ro: 'Suntem bucuroși să vă ajutăm. Contactați-ne prin orice mijloc convenabil.' },

  'contact.address.label': { ru: 'Адрес',          ro: 'Adresă' },
  'contact.address.value': { ru: 'ул. Примерная, 1, Кишинёв, Молдова', ro: 'str. Exemplu, 1, Chișinău, Moldova' },

  'contact.phone.label': { ru: 'Телефон',           ro: 'Telefon' },
  'contact.phone.value': { ru: '+373 00 000 000',   ro: '+373 00 000 000' },

  'contact.email.label': { ru: 'Email',             ro: 'Email' },
  'contact.email.value': { ru: 'contact@foryou.md', ro: 'contact@foryou.md' },

  'contact.hours.label':    { ru: 'Режим работы',           ro: 'Program de lucru' },
  'contact.hours.weekdays': { ru: 'Пн–Пт: 09:00 – 18:00',  ro: 'Lun–Vin: 09:00 – 18:00' },
  'contact.hours.weekend':  { ru: 'Сб: 10:00 – 15:00',     ro: 'Sâm: 10:00 – 15:00' },

  'contact.directions.title': { ru: 'Как нас найти', ro: 'Cum ne găsiți' },
  'contact.directions.desc':  { ru: 'Мы находимся в центре города. Ближайшая остановка — «Площадь Великого Национального Собрания». Есть парковка рядом с магазином.', ro: 'Suntem situați în centrul orașului. Cea mai apropiată stație este «Piața Marii Adunări Naționale». Există parcare în apropierea magazinului.' },

  'contact.map.noKey': { ru: 'Карта недоступна. Укажите VITE_GOOGLE_MAPS_KEY в .env', ro: 'Harta nu este disponibilă. Setați VITE_GOOGLE_MAPS_KEY în .env' },

  // Common
  'common.free':    { ru: 'Бесплатно', ro: 'Gratuit' },
  'common.yes':     { ru: 'Да',        ro: 'Da' },
  'common.loading': { ru: 'Загрузка…', ro: 'Se încarcă…' },

  // Home — Store title
  'home.store.badge':   { ru: 'Коллекция 2025',                                       ro: 'Colecția 2025' },
  'home.store.name':    { ru: 'Магазин верхней женской одежды',                        ro: 'Magazin de îmbrăcăminte caldă pentru femei' },
  'home.store.tagline': { ru: 'Куртки, пальто, шубы и жилеты — всё для тепла и стиля', ro: 'Jachete, paltoane, blănuri și veste — tot pentru căldură și stil' },
  'home.store.cta':     { ru: 'Смотреть каталог',                                      ro: 'Vezi catalogul' },

  // Home — Hero
  'home.hero.badge':    { ru: 'Новинки 2025',          ro: 'Noutăți 2025' },
  'home.hero.title1':   { ru: 'Откройте для себя',     ro: 'Descoperă produse' },
  'home.hero.title2':   { ru: 'товары, которые понравятся', ro: 'pe care le vei iubi' },
  'home.hero.subtitle': { ru: 'Тысячи товаров по конкурентным ценам. Бесплатная доставка при заказе от $50.', ro: 'Mii de articole la prețuri competitive. Livrare gratuită la comenzi peste $50.' },
  'home.hero.shop':     { ru: 'Купить сейчас',         ro: 'Cumpără acum' },
  'home.hero.catalog':  { ru: 'Смотреть каталог',      ro: 'Vezi catalogul' },

  // Home — Stats
  'home.stats.products':  { ru: 'Товаров',         ro: 'Produse' },
  'home.stats.customers': { ru: 'Клиентов',         ro: 'Clienți' },
  'home.stats.rating':    { ru: 'Средний рейтинг',  ro: 'Rating mediu' },
  'home.stats.support':   { ru: 'Поддержка',         ro: 'Suport' },

  // Home — Categories
  'home.cats.title':   { ru: 'По категориям', ro: 'Pe categorii' },
  'home.cats.viewAll': { ru: 'Все',           ro: 'Vezi tot' },

  // Home — New Arrivals
  'home.arrivals.title':  { ru: 'Новинки',             ro: 'Noutăți' },
  'home.arrivals.all':    { ru: 'Все товары →',         ro: 'Toate produsele →' },
  'home.arrivals.seeAll': { ru: 'Смотреть все товары',  ro: 'Vezi toate produsele' },

  // Home — Promo
  'home.promo.ship.title': { ru: 'Бесплатная доставка',  ro: 'Livrare gratuită' },
  'home.promo.ship.desc':  { ru: 'На все заказы от $50. Быстрая доставка за 2–3 рабочих дня.', ro: 'La toate comenzile peste $50. Livrare rapidă în 2–3 zile lucrătoare.' },
  'home.promo.shop':       { ru: 'Купить',               ro: 'Cumpără' },
  'home.promo.prot.title': { ru: 'Защита покупателя',    ro: 'Protecția cumpărătorului' },
  'home.promo.prot.desc':  { ru: '30-дневный возврат. Полный возврат, если товар не соответствует описанию.', ro: 'Returnare ușoară în 30 de zile. Ramburs complet dacă articolul nu corespunde descrierii.' },
  'home.promo.more':       { ru: 'Подробнее',            ro: 'Află mai mult' },

  // Home — Newsletter
  'home.news.title':     { ru: 'Будьте в курсе', ro: 'Fii la curent' },
  'home.news.desc':      { ru: 'Подпишитесь, чтобы узнавать о новых товарах и эксклюзивных акциях.', ro: 'Abonează-te pentru a fi notificat despre produse noi și oferte exclusive.' },
  'home.news.subscribe': { ru: 'Подписаться',    ro: 'Abonează-te' },
  'home.news.thanks':    { ru: 'Спасибо! Вы подписались.', ro: 'Mulțumim! Ești abonat.' },

  // Products page
  'products.title':       { ru: 'Товары',           ro: 'Produse' },
  'products.found':       { ru: 'товаров найдено',  ro: 'produse găsite' },
  'products.search':      { ru: 'Поиск товаров...', ro: 'Caută produse...' },
  'products.clearFilter': { ru: 'Сбросить фильтры', ro: 'Resetează filtrele' },
  'products.perPage':     { ru: '/ стр.',            ro: '/ pag.' },
  'products.empty.title': { ru: 'Товары не найдены', ro: 'Nu s-au găsit produse' },
  'products.empty.hint':  { ru: 'Попробуйте изменить фильтры или поисковый запрос', ro: 'Încearcă să ajustezi filtrele sau interogarea de căutare' },
  'products.page':        { ru: 'Страница',           ro: 'Pagina' },
  'products.of':          { ru: 'из',                 ro: 'din' },

  // Product detail
  'product.badge.new':      { ru: 'Новинка',  ro: 'Nou' },
  'product.badge.hit':      { ru: 'Хит',      ro: 'Hit' },
  'product.badge.sale':     { ru: 'Скидка',   ro: 'Reducere' },
  'product.qty':            { ru: 'Количество:', ro: 'Cantitate:' },
  'product.addToCart':      { ru: 'В корзину',   ro: 'Adaugă în coș' },
  'product.adding':         { ru: 'Добавляем...', ro: 'Se adaugă...' },
  'product.added':          { ru: 'Добавлен',    ro: 'Adăugat' },
  'product.spec.article':   { ru: 'Артикул',           ro: 'Articol' },
  'product.spec.season':    { ru: 'Сезон',             ro: 'Sezon' },
  'product.spec.length':    { ru: 'Длина',             ro: 'Lungime' },
  'product.spec.outer':     { ru: 'Внешний материал',  ro: 'Material exterior' },
  'product.spec.lining':    { ru: 'Подкладка',         ro: 'Căptușeală' },
  'product.spec.filling':   { ru: 'Наполнитель',       ro: 'Umplutură' },
  'product.spec.hood':      { ru: 'Капюшон',           ro: 'Glugă' },
  'product.spec.hoodYes':   { ru: 'Есть',              ro: 'Da' },
  'product.spec.hoodDetach':{ ru: 'Есть, съёмный',     ro: 'Da, detașabil' },
  'product.spec.waterproof':{ ru: 'Водонепроницаемый', ro: 'Impermeabil' },
  'product.spec.delivery':  { ru: 'Доставка',          ro: 'Livrare' },
  'product.spec.days':      { ru: '2–3 дня',           ro: '2–3 zile' },
  'product.notFound':       { ru: 'Товар не найден',   ro: 'Produsul nu a fost găsit' },
  'product.backTo':         { ru: 'Назад к товарам',   ro: 'Înapoi la produse' },
  'product.in_stock':       { ru: 'В наличии',         ro: 'În stoc' },
  'product.pcs':            { ru: 'шт.',               ro: 'buc.' },
  'product.choose_params':  { ru: 'Пожалуйста, выберите параметры',         ro: 'Vă rugăm să selectați opțiunile' },
  'product.size':           { ru: 'Размер',            ro: 'Mărimea' },
  'product.color':          { ru: 'Цвет',            ro: 'Culoare' },

  // Cart
  'cart.title':           { ru: 'Корзина',           ro: 'Coș de cumpărături' },
  'cart.placed.title':    { ru: 'Заказ оформлен!',   ro: 'Comandă plasată!' },
  'cart.continue':        { ru: 'Продолжить покупки', ro: 'Continuă cumpărăturile' },
  'cart.summary':         { ru: 'Итого',             ro: 'Sumar' },
  'cart.subtotal':        { ru: 'Подытог:',          ro: 'Subtotal:' },
  'cart.shipping':        { ru: 'Доставка:',         ro: 'Livrare:' },
  'cart.tax':             { ru: 'НДС (10%):',        ro: 'TVA (10%):' },
  'cart.total':           { ru: 'Итого:',            ro: 'Total:' },
  'cart.placeOrder':      { ru: 'Оформить заказ',    ro: 'Plasează comanda' },
  'cart.promo':           { ru: 'Промокод',          ro: 'Cod promoțional' },
  'cart.promoCode':       { ru: 'Код',               ro: 'Cod' },
  'cart.apply':           { ru: 'Применить',         ro: 'Aplică' },
  'cart.empty.title':     { ru: 'Корзина пуста',     ro: 'Coșul tău este gol' },
  'cart.empty.hint':      { ru: 'Добавьте товары',   ro: 'Adaugă produse' },
  'cart.empty.back':      { ru: 'К товарам',         ro: 'Înapoi la produse' },
  'cart.dialog.title':    { ru: 'Подтвердить заказ', ro: 'Confirmă comanda' },
  'cart.dialog.desc':     { ru: 'Оставьте контактные данные, чтобы мы могли с вами связаться (необязательно).', ro: 'Lasă datele de contact pentru a te putea contacta (opțional).' },
  'cart.dialog.name':     { ru: 'Имя',               ro: 'Nume' },
  'cart.dialog.namePh':   { ru: 'Ваше имя',          ro: 'Numele tău' },
  'cart.dialog.phone':    { ru: 'Телефон',            ro: 'Telefon' },
  'cart.dialog.items':    { ru: 'Товары:',            ro: 'Articole:' },
  'cart.dialog.total':    { ru: 'Итого:',             ro: 'Total:' },
  'cart.dialog.cancel':   { ru: 'Отмена',             ro: 'Anulează' },
  'cart.dialog.placing':  { ru: 'Оформляем...',       ro: 'Se procesează...' },
  'cart.dialog.confirm':  { ru: 'Подтвердить',        ro: 'Confirmă' },

  // Aside filters
  'filter.title':      { ru: 'Фильтры',            ro: 'Filtre' },
  'filter.clearAll':   { ru: 'Сбросить',           ro: 'Resetează' },
  'filter.price':      { ru: 'Цена (lei)',          ro: 'Preț (lei)' },
  'filter.priceFrom':  { ru: 'От',                 ro: 'De la' },
  'filter.priceTo':    { ru: 'До',                 ro: 'Până la' },
  'filter.category':   { ru: 'Категория',          ro: 'Categorie' },
  'filter.outer':      { ru: 'Материал',           ro: 'Material' },
  'filter.lining':     { ru: 'Подкладка',          ro: 'Căptușeală' },
  'filter.filling':    { ru: 'Наполнитель',        ro: 'Umplutură' },
  'filter.season':     { ru: 'Сезон',              ro: 'Sezon' },
  'filter.length':     { ru: 'Длина',              ro: 'Lungime' },
  'filter.features':   { ru: 'Особенности',        ro: 'Caracteristici' },
  'filter.hood':       { ru: 'Есть капюшон',       ro: 'Cu glugă' },
  'filter.waterproof': { ru: 'Водонепроницаемый',  ro: 'Impermeabil' },
  'filter.color':      { ru: 'Цвет',               ro: 'Culoare' },
  'filter.size':       { ru: 'Размер',              ro: 'Mărime' },
  'filter.apply':      { ru: 'Применить',           ro: 'Aplică' },
  'filter.open':       { ru: 'Фильтры',             ro: 'Filtre' },

  // ProductCard
  'card.view':  { ru: 'Подробнее', ro: 'Detalii' },
  'card.add':   { ru: 'В корзину', ro: 'În coș' },
  'card.added': { ru: 'В корзине', ro: 'Adăugat' },

  // Profile / personal cabinet
  'profile.title':           { ru: 'Личный кабинет',          ro: 'Cabinet personal' },
  'profile.subtitle':        { ru: 'Ваши данные и история заказов', ro: 'Datele tale și istoricul comenzilor' },
  'profile.orders.title':    { ru: 'История заказов',          ro: 'Istoricul comenzilor' },
  'profile.orders.empty':    { ru: 'У вас пока нет заказов',    ro: 'Nu ai încă nicio comandă' },
  'profile.orders.empty.cta':{ ru: 'Перейти к покупкам',        ro: 'Mergi la cumpărături' },
  'profile.orders.itemsCount': { ru: 'товаров',                ro: 'produse' },
  'profile.orders.toggle.more': { ru: 'Ещё',                   ro: 'Mai multe' },
  'profile.orders.toggle.less': { ru: 'Свернуть',              ro: 'Restrânge' },

  // Auth — shared fields
  'auth.fields.name':            { ru: 'Имя',                 ro: 'Nume' },
  'auth.fields.surname':         { ru: 'Фамилия',             ro: 'Prenume' },
  'auth.fields.email':           { ru: 'Email',               ro: 'Email' },
  'auth.fields.password':        { ru: 'Пароль',              ro: 'Parolă' },
  'auth.fields.passwordHint':    { ru: 'Минимум 5 символов',  ro: 'Minimum 5 caractere' },
  'auth.fields.confirmPassword': { ru: 'Повторите пароль',    ro: 'Repetă parola' },
  'auth.or':                     { ru: 'или',                 ro: 'sau' },
  'auth.google':                 { ru: 'Google',              ro: 'Google' },

  // Auth — Login
  'auth.login.title':       { ru: 'Вход',                                   ro: 'Autentificare' },
  'auth.login.subtitle':    { ru: 'Введите данные своей учётной записи',     ro: 'Introduceți datele contului dvs.' },
  'auth.login.panelTitle':  { ru: 'С возвращением',                          ro: 'Bine ai revenit' },
  'auth.login.panelDesc':   { ru: 'Войдите, чтобы продолжить покупки, отслеживать заказы и сохранять любимые товары.', ro: 'Autentifică-te pentru a continua cumpărăturile, a urmări comenzile și a salva produsele favorite.' },
  'auth.login.remember':    { ru: 'Запомнить меня',                          ro: 'Ține-mă minte' },
  'auth.login.submit':      { ru: 'Войти',                                   ro: 'Autentificare' },
  'auth.login.submitting':  { ru: 'Входим…',                                 ro: 'Se conectează…' },
  'auth.login.noAccount':   { ru: 'Нет аккаунта?',                           ro: 'Nu ai cont?' },
  'auth.login.toRegister':  { ru: 'Зарегистрироваться',                      ro: 'Înregistrează-te' },
  'auth.login.success':     { ru: 'Добро пожаловать!',                       ro: 'Bine ai venit!' },
  'auth.login.error':       { ru: 'Неверный email или пароль',               ro: 'Email sau parolă incorectă' },

  // Auth — Register
  'auth.register.title':      { ru: 'Регистрация',                                            ro: 'Înregistrare' },
  'auth.register.subtitle':   { ru: 'Создайте аккаунт, чтобы оформлять заказы быстрее',         ro: 'Creează un cont pentru a plasa comenzi mai rapid' },
  'auth.register.panelTitle': { ru: 'Добро пожаловать',                                        ro: 'Bine ai venit' },
  'auth.register.panelDesc':  { ru: 'Создайте аккаунт и получите доступ к заказам, избранному и персональным предложениям.', ro: 'Creează un cont și ai acces la comenzi, favorite și oferte personalizate.' },
  'auth.register.submit':     { ru: 'Создать аккаунт',                                         ro: 'Creează cont' },
  'auth.register.submitting': { ru: 'Создаём аккаунт…',                                        ro: 'Se creează contul…' },
  'auth.register.haveAccount':{ ru: 'Уже есть аккаунт?',                                       ro: 'Ai deja cont?' },
  'auth.register.toLogin':    { ru: 'Войти',                                                   ro: 'Autentifică-te' },
  'auth.register.success':    { ru: 'Аккаунт успешно создан',                                  ro: 'Cont creat cu succes' },
  'auth.register.error':      { ru: 'Не удалось создать аккаунт',                              ro: 'Nu s-a putut crea contul' },
}

export function useI18n() {
  const localeStore = useLocaleStore()
  const t = (key) => {
    const entry = ui[key]
    if (!entry) return key
    return localeStore.t(entry)
  }
  return { t }
}
