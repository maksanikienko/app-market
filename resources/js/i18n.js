import { useLocaleStore } from '@/store/localeStore'

const ui = {
  // Not found
  'notFound.title':   { ru: 'Страница не найдена',                         ro: 'Pagina nu a fost găsită' },
  'notFound.hint':    { ru: 'Возможно, ссылка устарела или адрес введён с ошибкой.', ro: 'Este posibil ca linkul să fie expirat sau adresa să fie greșită.' },
  'notFound.home':    { ru: 'На главную',                                  ro: 'Acasă' },
  'notFound.catalog': { ru: 'Перейти в каталог',                           ro: 'Vezi catalogul' },

  // Navigation
  'nav.home':     { ru: 'Главная',               ro: 'Acasă' },
  'nav.products': { ru: 'Товары',                ro: 'Produse' },
  'nav.contact':  { ru: 'Контакты',              ro: 'Contact' },
  'nav.login':    { ru: 'Войти',                 ro: 'Autentificare' },
  'nav.register': { ru: 'Регистрация',            ro: 'Înregistrare' },
  'nav.admin':    { ru: 'Панель администратора', ro: 'Panou admin' },
  'nav.logout':   { ru: 'Выйти',                 ro: 'Ieșire' },
  'nav.profile':  { ru: 'Мои заказы',            ro: 'Comenzile mele' },

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
  'common.yes':     { ru: 'Да',        ro: 'Da' },

  // Home — Store title
  'home.store.name':    { ru: 'Магазин верхней женской одежды',                        ro: 'Magazin de îmbrăcăminte caldă pentru femei' },
  'home.store.tagline': { ru: 'Куртки, пальто, шубы и жилеты — всё для тепла и стиля', ro: 'Jachete, paltoane, blănuri și veste — tot pentru căldură și stil' },
  'home.store.cta':     { ru: 'Смотреть каталог',                                      ro: 'Vezi catalogul' },

  // Home — Categories
  'home.cats.title':   { ru: 'По категориям', ro: 'Pe categorii' },
  'home.cats.viewAll': { ru: 'Все',           ro: 'Vezi tot' },

  // Home — New Arrivals
  'home.arrivals.title':  { ru: 'Новинки',             ro: 'Noutăți' },
  'home.arrivals.all':    { ru: 'Все товары →',         ro: 'Toate produsele →' },
  'home.arrivals.seeAll': { ru: 'Смотреть все товары',  ro: 'Vezi toate produsele' },

  // Products page
  'products.title':       { ru: 'Товары',           ro: 'Produse' },
  'products.found':       { ru: 'товаров найдено',  ro: 'produse găsite' },
  'products.search':      { ru: 'Поиск товаров...', ro: 'Caută produse...' },
  'products.clearFilter': { ru: 'Сбросить фильтры', ro: 'Resetează filtrele' },
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
  'cart.placed.message':  { ru: 'Заказ #:id успешно оформлен. Мы свяжемся с вами в ближайшее время.', ro: 'Comanda #:id a fost plasată cu succes. Vă vom contacta în curând.' },
  'cart.placed.title':    { ru: 'Заказ оформлен!',   ro: 'Comandă plasată!' },
  'cart.error.update':    { ru: 'Не удалось изменить количество', ro: 'Nu s-a putut modifica cantitatea' },
  'cart.error.remove':    { ru: 'Не удалось удалить товар',       ro: 'Nu s-a putut elimina produsul' },
  'cart.continue':        { ru: 'Продолжить покупки', ro: 'Continuă cumpărăturile' },
  'cart.summary':         { ru: 'Итого',             ro: 'Sumar' },
  'cart.subtotal':        { ru: 'Подытог:',          ro: 'Subtotal:' },
  'cart.tax':             { ru: 'НДС (10%):',        ro: 'TVA (10%):' },
  'cart.total':           { ru: 'Итого:',            ro: 'Total:' },
  'cart.placeOrder':      { ru: 'Оформить заказ',    ro: 'Plasează comanda' },
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
  'card.add':   { ru: 'В корзину', ro: 'În coș' },
  'card.added': { ru: 'В корзине', ro: 'Adăugat' },

  // Auth — shared fields
  'auth.fields.name':            { ru: 'Имя',                 ro: 'Nume' },
  'auth.fields.email':           { ru: 'Email',               ro: 'Email' },
  'auth.fields.password':        { ru: 'Пароль',              ro: 'Parolă' },
  'auth.fields.passwordHint':    { ru: 'Минимум 5 символов',  ro: 'Minimum 5 caractere' },
  'auth.fields.confirmPassword': { ru: 'Повторите пароль',    ro: 'Repetă parola' },
  'auth.home':                   { ru: 'На главную',          ro: 'Acasă' },
  'auth.or':                     { ru: 'или',                 ro: 'sau' },
  'auth.google':                 { ru: 'Google',              ro: 'Google' },

  // Auth — Login
  'auth.login.title':       { ru: 'Вход',                                   ro: 'Autentificare' },
  'auth.login.subtitle':    { ru: 'Введите данные своей учётной записи',     ro: 'Introduceți datele contului dvs.' },
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
  'auth.register.submit':     { ru: 'Создать аккаунт',                                         ro: 'Creează cont' },
  'auth.register.submitting': { ru: 'Создаём аккаунт…',                                        ro: 'Se creează contul…' },
  'auth.register.haveAccount':{ ru: 'Уже есть аккаунт?',                                       ro: 'Ai deja cont?' },
  'auth.register.toLogin':    { ru: 'Войти',                                                   ro: 'Autentifică-te' },
  'auth.register.success':    { ru: 'Аккаунт успешно создан',                                  ro: 'Cont creat cu succes' },
  'auth.register.error':      { ru: 'Не удалось создать аккаунт',                              ro: 'Nu s-a putut crea contul' },

  // Profile
  'profile.orders.title': { ru: 'Мои заказы',        ro: 'Comenzile mele' },
  'profile.orders.empty': { ru: 'Заказов пока нет',  ro: 'Nicio comandă încă' },
  'profile.orders.count': { ru: 'заказа',            ro: 'comenzi' },
  'profile.order.id':     { ru: 'Заказ',             ro: 'Comandă' },
  'profile.order.total':  { ru: 'Итого',             ro: 'Total' },
  'profile.order.qty':    { ru: 'Кол-во',            ro: 'Cantitate' },
  'profile.order.price':  { ru: 'Сумма',             ro: 'Sumă' },
  'profile.order.name':   { ru: 'Товар',             ro: 'Produs' },
}

export function useI18n() {
  const localeStore = useLocaleStore()

  // t('cart.placed.message', { id: 5 }) replaces `:id` placeholders
  const t = (key, params = {}) => {
    const entry = ui[key]
    if (!entry) return key
    return Object.entries(params).reduce(
      (text, [name, value]) => text.replaceAll(`:${name}`, value),
      localeStore.t(entry),
    )
  }

  return { t }
}
