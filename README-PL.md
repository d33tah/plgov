plgov - o projekcie
===================

plgov jest projektem, który pozwala na przeszukanie całej historii Wikipedii
pod kątem edycji stron dokonanych przez polski rząd. W tym celu odczytuje on
publicznie dostępne archiwum zmian Wikipedii i filtruje je, szukając poprawek
naniesionych przez niezarejestrowanych użytkowników, których adres IP należy
do wskazanej listy. Następnie możliwe jest przygotowanie raportu, takiego jak
ten dostępny na stronie: https://git.io/plgov

Jak czytać ten raport?
======================

Na stronie: https://git.io/plgov dostępna jest tabelka zawierająca sześć
tysięcy zmian w Wikipedii. Dane podzielone są na sześć kolumn: IP, rDNS,
tytuł, data, link do strony ze zmianami oraz ilość osób, która obejrzała tę
konkretną zmianę.

Pierwsza kolumna, adres IP, to numer komputera w Internecie, który dokonał tej
zmiany. W tej samej kolumnie znajduje się łącze oznaczone [W], gdzie można
wygodnie sprawdzić w internetowym rejestrze WHOIS, do kogo w tym momencie
należy ten adres. Druga kolumna to rDNS, czyli nazwa hosta przypisana do tego
adresu IP, ustawiona przez administratora sieci. Trzecia i czwarta kolumna 
mówią o tym, jaki artykuł i kiedy zmieniono.

Piąta kolumna jest prawdopodobnie najbardziej interesująca - to łącza do 
polskiej Wikipedii, gdzie można sprawdzić, czego konkretnie dotyczyła ta
pojedyncza zmiana. Pierwszy link, nazwany "LINK", prowadzi do mobilnej wersji
strony, która może być czytelniejsza dla osób nie znających szczegółów
działania Wikipedii. Wskazane tam poprawki zaznaczono na zielono, jeśli dany
fragment został dopisany w ramach wyświetlanej zmiany. Kolor czerwony oznacza,
że fragment został usunięty, a biały kolor pokazuje kontekst, w którym
dokonano zmiany.

Drugie łącze w piątej kolumnie, oznaczone jako "[2]" zawiera wersję strony nie
przeznaczoną dla urządzeń mobilnych. Można w niej znaleźć więcej informacji,
a na dole strony - jej wersję po poprawkach.

Ostatnia kolumna to przybliżona ilość osób (mierzona ilością adresów IP), która
obejrzała daną zmianę korzystając z tego raportu. Aby ją ustalić, linki
z piątej kolumny wskazują najpierw na serwer, z którego pochodzi raport, który
zapisuje, które zmiany zostały obejrzane przez adres IP odwiedzającego. Żadne
inne dane o osobach odwiedzających serwis nie są zbierane.

W jaki sposób wykonano ten eksperyment?
=======================================

Badanie wykonano w ciągu kilku dni na komputerze z procesorem i7-3770,
wyposażonym w 8GiB pamięci RAM oraz dysk twardy z przynajmniej 30GiB wolnego
miejsca. Komputer był podłączony do szerokopasmowego internetu oraz miał
zainstalowany system Fedora Linux w wersji 21. Proces zaczęto od pobrania
czterech archiwów Wikipedii z dnia 19 lutego 2015r., oraz archiwum rDNS firmy
Rapid7 ("Sonar") z dnia 11 marca 2015r., dostępnych w dniu badania pod
poniższymi adresami: 

https://dumps.wikimedia.org/plwiki/20150219
(pliki: "All pages with complete page edit history (.bz2)")

https://scans.io/data/rapid7/sonar.rdns/20150311-rdns.gz

Następnie, z pliku 20150311-rdns.gz przy pomocy programu wybrano wszystkie
adresy IP z adresem rDNS kończącym się na .gov.pl i zapisano je do pliku
iplist.txt. Po tym kroku użyto programu etree.py, wskazując mu pojedynczo
każdy z archiwów plwiki-20150219-pages-meta-historyN.xml.bz2 (gdzie N to
liczby od 1 do 4 włącznie) oraz plik iplist.txt. Ostatnim krokiem było
przygotowanie środowiska front-end korzystając ze skryptów dostępnych w
katalogu frontend. Zarówno etree.py jak i katalog frontend dostępne są pod
poniższym adresem:

https://github.com/d33tah/plgov

Na ile wiarygodne są te dane?
=============================

Aby pozwolić na uczciwą ocenę tych danych, należy zwrócić uwagę na kilka wad
w aktualnej metodzie pozyskiwania danych. Przede wszystkim, informacja o tym,
czy dany adres IP należy do instytucji rządowych, została wykonana w oparciu
o aktualny zbiór rDNS, podczas gdy część zmian została naniesiona kilka lat
temu. W tym czasie dany adres IP mógł należeć do innego właściciela, dlatego
im dawniej zostały naniesione dane poprawki do Wikipedii, tym mniej wiarygodne
mogą one być.

Dane są także niekompletne - uwzględniono tylko adresy IP posiadające rekord
rDNS kończący się na ".gov.pl". Raport nie zawiera także zmian dokonanych
przez zarejestrowanych użytkowników.

Z technicznego punktu widzenia, część zmian mogła być wykonana przez osoby nie
będące pracownikami instytucji rządowych. Możliwe jest, że - przykładowo -
komputer podłączony do sieci Ministerstwa Finansów udostępniał publiczną sieć
bezprzewodową, z której korzystały osoby nieuprawnione. Dodatkowo, istnieje
prawdopodobieństwo, że któryś z komputerów nie należących do rządowej 
infrastruktury ustawił swój adres rDNS na taki, który kończy się na .gov.pl,
choć jest to w ocenie autora mało prawdopodobne.

Niezależnie od wyżej wspomnianych problemów, projekt został wykonany z dużą
starannością, a jego wyniki pracy można odtworzyć.