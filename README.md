## Zadanie rekrutacyjne

Aplikacja ATS pozwalająca zarządzać projektami rekrutacyjnymi oraz kandydatami do pracy.

Aktualnie aplikacja posiada tylko dwa widoki: lista projektów rekrutacyjnych oraz szczegóły projektu.<br>
Możliwe do wykonania są dwie podstawowe akcje, dodawanie oraz edycja projektu rekrutacyjnego.

### Co jest do zrobienia

1. Dodaj do encji AtsProject parametr pozwalający określić czy projekt jest aktywny czy nie. Stwórz odpowiednią migrację
   modyfikującą tabelę w bazie danych. Dodaj obsługę nowego parametru w formularzu edycji/dodawania projektu.
2. Dodaj możliwość tworzenia nowego projektu rekrutacyjnego z poziomu CLI. Argumentami powinna być nazwa projektu oraz adres e-mail managera projektu. Tworzony projekt powinien być domyślnie aktywny.
3. Rozbuduj aplikację o obsługę kandydatów, którzy są przypisani do projektu rekrutacyjnego:
   - Dodaj obsługę dodawania/edycji kandydata.
   - Kandydata dodajemy w widoku szczegółów projektu rekrutacyjnego.
   - Kandydat może być dodany tylko w ramach aktywnego projektu rekrutacyjnego. Napisz test sprawdzający ten warunek.
   - Każdy kandydat jest powiązany z jednym projektem rekrutacyjnym.
   - Kandydat jest opisany przez:
     - Imię (pole obowiązkowe)
     - Nazwisko (pole obowiązkowe)
     - Adres e-email (pole obowiązkowe)
   - Stwórz odpowiednią migrację modyfikującą bazę danych
   - Po dodaniu kandydata w ramach projektu, manager projektu powinien dostać stosowne powiadomienie.
   - W widoku szczegółów projektu rekrutacyjnego dodaj listę kandydatów.
4. Dodaj możliwość usuwania projektu rekrutacyjnego
    - Usuwamy projekt rekrutacyjny oraz powiązanych z nim kandydatów.
    - Manager projektu powinien dostać stosowne powiadomienie.
5. W miarę możliwości napisz testy jednostkowe zabezpieczające wprowadzone zmiany.

Do wysyłania powiadomień wykorzystaj SendEmailAction, nie trzeba tworzyć rzeczywistej wysyłki maila.

### Jak wykonać zadanie
Aby zacząć pracę zrób forka tego repozytorium. Po skończeniu pracy wyślij do nas Pull Request.<br>
Postaraj się zaplanować pracę i podzielić ją na logiczne części, które będą odzwierciedlone w commitach do repozytorium.<br>
<b>Ważne: Podczas pisania kodu trzymaj się zasad i wzorców już używanych w aplikacji.</b>

### Uruchomienie aplikacji

Zalecamy używanie Symfony CLI

```
# instalowanie zależności
composer update

# sprawdzenie wymagań aplikacji
symfony check:requirements

# utworzenie bazy danych + uruchomienie DM + załadowanie fixtures + tailwind
composer app-setup

# uruchomienie aplikacji
symfony server:start
```

### Testy
```
bin/phpunit
```
