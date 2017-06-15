# ksl-micro
микро-фреймворк PHP

Данный фреймворк я создал для работы над своими небольшими проектами. Для больших предпочитаю использование Laravel-5 или Yii2. 

Приставку "микро" дал в связи с тем, что он содержит только самые необходимые инструменты. Тем не менее заложена основа для его расширения в виде реализации сервис-контейнера. 
Фреймворк основан на использовании шаблона проектирования MVC.

В структуре уже созданы некоторые тестовые классы и файлы (контроллер, модель, вид и др.) для демонстрации войможностей. Так же, в корне проекта, есть дамп тестовой базы данных.


УСТАНОВКА.

Для установки и тестирования клонируйте этот репозиторий https://github.com/klisl/ksl-micro.git
или просто скачайте архив https://github.com/klisl/ksl-micro/archive/master.zip
Создайте базу данных MySql (по-умолчанию "test") и в файле config.php внесите данные для соединения с ней. Импортируйте в нее тестовый дамп из корня приложения для тестирования. 
Все, после перезагрузки локального сервера, можно тестировать. 

Создан тестовый контроллер ksl-micro\app\controllers\SiteController.php содержащий несколько действий (методов) выводящих:
- главную страницу с выводом всех постов http://ksl-micro
- страницу отдельного поста http://ksl-micro/post1.html
- статичную страницу http://ksl-micro/contacts.html
- страницу ошибки 404(Not Found)


КРАТКОЕ ОПИСАНИЕ ОСНОВНЫХ КОМПОНЕНТОВ.

В каталоге public я разместил "точку входа" - файл index.php и другие публичные файлы к которым есть доступ из вне.
Каталог vendor содержит служебные классы фреймворка.
Каталог app содержит пользовательские классы и файлы реализующие шаблон MVC.

Разобраться в работе моего микро-фреймворка не сложно. В точке входа (файле index.php) создается объект приложения - экземпляр класса Application, который, первым делом, создает объект сервис-контейнера, используемый в дальнейшем на протяжении всей работы приложения. Сервис-контейнер создан реализацией шаблона проектирования "Singleton" и предоставляет доступ к любому объекту хранимому в нем. Так же, он позволяет использовать "внедрение зависимостей" в конструктор нужного класса.

"Из коробки" реализована работа с ЧПУ ссылок, что необходимо для SEO оптимизации. При этом используются алиасы, прописанные в БД в таблице url_alias. Работу ЧПУ обеспечивает экземпляр класса Router. Есть возможность переключиться (в настройках приложения) на использование URL такого плана:
http://ksl-micro/index.php?route=site/post&id=1

Просмотрев код класса Application, можно получить представление о прохождении запроса на сервер и формировании ответа браузеру. 
Прежде всего, создается объект Request, получающий и сохраняющий данные запроса. 
Далее вызывается объект класса Router, который работает с get-параметрами запроса, выделяя контроллер и действие, которые должны будут его обработать. При этом, если в настройках стоит использование ЧПУ ссылок, то создается подключение к базе данных, где в таблице "url_alias" происходит поиск контроллера/действия в зависимости от полученного алиаса из URL.
Далее в работу включается объект класса Action, который формирует полный путь к файлу контроллера, подключает его файл, создает экземпляр класса контроллера и вызывает нужное действие с передачей аргументов (используется Reflection API).
Получив  и обработав данные с помощью модели, в контроллере вызывается метод render() создающий экземпляр класса View, передавая его конструктору в качестве аргументов название нужного файла представления и массив данных для вывода. 
Метод index() данного объекта определяет общий шаблон и подключает файл-представление используя буферизацию вывода.
В итоге контент страницы сохраняется в переменную и передается в объект Response для формирования окончательного ответа сервера. Данный объект формирует нужные заголовки браузеру. В конце работы приложения вызывается echo() для вывода окончательного результа на экран.

В каталоге "app", в соответствующих подпапках, необходимо размещать свои контроллеры, модели, файлы представлений, виджеты и прочее. Код тестового контроллера и других компонентов я максимально прокомментировал (на русском), для получения представления о использовании данного микро-фреймворка. В связи с этим не буду подробно здесь описывать.

Для удобства использования, в каталоге "app", создан файл helpers.php, содержащий глобально-доступные функции:
- app() предоставляет доступ к сервис-контейнеру, а при передаче параметра - возвращает нужный объект из контейнера;
- get() и post() предоставляют доступ к параметрам соответствующего запроса;
- url() облегчает процесс формирования ссылок на другие страницы;
- debug() выводит содержимое нужной переменной на экран в удобном для анализа виде (полезно для отладки)
и др.

Создан класс Session для удобной работы с сессиями через статический интерфейс.
Для загрузки служебных и пользовательских классов используется автозагрузчик классов "ClassLoader", который позволяет загружать классы из списка указанных каталогов или списка классов указанных в карте классов.


http://klisl.com