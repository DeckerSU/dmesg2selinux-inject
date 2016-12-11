Что это и для чего?
-------------------

PHP скрипт, который позволяет на основе логов dmesg найти по паттерну *avc: denied* все предупреждения и на основе их сформировать скрипт для добавления соответствующих правил в бинарник sepolicy с помощью утилиты sepolicy_inject - [https://bitbucket.org/joshua_brindle/sepolicy-inject](https://bitbucket.org/joshua_brindle/sepolicy-inject) .

Запуск
------

Положите лог dmesg в файл dmesg.txt и запустите скрипт:

**php dmesg2selinux-inject.php > results.sh**

Пример результата
-----------------
    
    sepolicy-inject -s ueventd -t unlabeled -c chr_file -p create,setattr -P ./sepolicy
    sepolicy-inject -s ueventd -t unlabeled -c blk_file -p create,setattr -P ./sepolicy
    sepolicy-inject -s ueventd -t ueventd -c capability2 -p mac_admin -P ./sepolicy
    sepolicy-inject -s init -t system_file -c file -p relabelfrom -P ./sepolicy
    sepolicy-inject -s init -t unlabeled -c chr_file -p setattr -P ./sepolicy
    sepolicy-inject -s init -t nvram_data_file -c lnk_file -p read -P ./sepolicy
    sepolicy-inject -s init -t shell_exec -c file -p execute_no_trans -P ./sepolicy
    sepolicy-inject -s init -t property_socket -c sock_file -p write -P ./sepolicy
    sepolicy-inject -s init -t logmisc_data_file -c file -p append -P ./sepolicy
    sepolicy-inject -s toolbox -t toolbox -c capability -p dac_override -P ./sepolicy
    sepolicy-inject -s logd -t unlabeled -c blk_file -p read,write,open -P ./sepolicy
    sepolicy-inject -s servicemanager -t init -c file -p read,open -P ./sepolicy
    sepolicy-inject -s servicemanager -t init -c process -p getattr -P ./sepolicy
    sepolicy-inject -s themeservice_app -t system_data_file -c dir -p write,add_name,create,setattr -P ./sepolicy

Правила не дублируются, то есть собираются по s, t, c.

p.s. Если вы нашли какие-то ошибки в регулярном выражении или какое-то правило в вашем dmesg не обрабатывается - буду рад замечаниям.

Полезные ссылки
---------------

- О том как собрать sepolicy-inject и полностью тулсет с поддержкой android (v30) selinux policy на базе исходников CM13 можно почитать здесь - [http://android.stackexchange.com/questions/128965/examine-android-v30-selinux-policy](http://android.stackexchange.com/questions/128965/examine-android-v30-selinux-policy) .

WBR, Decker [ [http://www.decker.su](http://www.decker.su) ]
