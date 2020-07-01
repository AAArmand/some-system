## Пояснение
Реализовал получение курсов валют через цепочку обработчиков. Каждый обработчик знает что ему нужно сделать, либо достать данные из своего хранилища, проделав при этом те или иные действия, либо передать ответственность следующему обработчику. Доступ к системе получения курсов скрыл в фасаде, чтобы клиентский код знал как можно меньше о деталях реализации.

## Мотивы
Стремился сделать "доставателей" курсов максимально обособленными друг от друга, поэтому каждому из них ижектятся свои зависимости. В то же время на уровне фасада мы можем управлять порядком их вызова, что позволяет выстроить нужную нам/бизнесу упорядоченную цепочку и добавить новых "доставателей".Также стремился покрыть тестами все кроме исключительсных ситуаций (о них далее) и репозиториев, так как сами репозитории реализовывать по условию ТЗ не нужно.

## Не указанное поведение 
Не указано, что делать и как действовать в исключительных ситуациях. Например, если в http клиенте тоже не оказалось курсов или произошел сбой, или при получении данных из базы/кэша произошел системный сбой. Мои решения выглядели бы выбросом соотвествующего (на каждый случай своего) кастомного эксепшена, живущего в неймспейсе курсов валют и обработки его либо на уровне метода фасада, либо еще выше на уровне контроллера (я склоняюсь к контроллеру). Также ничего не было сказано про логирование действий и в принципе структуру ответа от http клиента. Зная структуру можно более точно сформировать модель курса валюты или напистаь отдельный мапер данных, если структура ответа слишком сложная.

