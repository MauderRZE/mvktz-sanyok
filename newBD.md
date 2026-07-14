# Технічна документація архітектури БД "mvktz" (Частина 1)

Цей документ містить детальний опис призначення, типів даних, індексів та правил підтримування цілісності (Foreign Keys) для перших 12 таблиць системи обліку ІТ-інфраструктури та матеріальних цінностей.

---

## 1. Таблиця `assets` (Матеріальні та віртуальні активи / Складові ТЦ)
Головна таблиця системи, яка поєднує фізичне обладнання з його віртуальною логікою (наприклад, окремі комплектуючі всередині системного блока, мережеві налаштування, прив'язку до локацій та відповідальних осіб).

### Структура полів
| Назва поля | Тип даних | Налаштування | Опис призначення |
| :--- | :--- | :--- | :--- |
| **`id`** | `INT` | PK, Auto Increment, NOT NULL | Унікальний системний ID активу. |
| **`base_component_id`**| `INT` | DEFAULT NULL, FK | Посилання на тип компонента (процесор, монітор тощо). |
| **`model_id`** | `INT` | DEFAULT NULL, FK | Посилання на конкретну модель з довідника моделей. |
| **`serial_number`** | `VARCHAR(45)` | DEFAULT NULL | Заводський серійний номер пристрою. |
| **`current_loc_id`** | `INT` | DEFAULT NULL, FK | Поточне фізичне розміщення (кабінет / кімната). |
| **`current_holder_id`**| `INT` | DEFAULT NULL, FK | Поточний матеріально відповідальний утримувач. |
| **`equipment_id`** | `INT` | DEFAULT NULL, FK | Зв'язок із бухгалтерським інвентарним номером. |
| **`parent_asset_id`** | `INT` | DEFAULT NULL, FK | Ієрархічний зв'язок (наприклад, ID системного блока для ОЗУ). |
| **`notes`** | `VARCHAR(255)`| DEFAULT NULL | Додаткові технічні примітки. |
| **`ip_address`** | `VARCHAR(45)` | DEFAULT NULL | Мережева IP-адреса пристрою (IPv4 / IPv6). |
| **`mac_address`** | `VARCHAR(17)` | DEFAULT NULL | Фізична MAC-адреса мережевої карти. |
| **`hostname`** | `VARCHAR(100)`| DEFAULT NULL | Мережеве ім'я комп'ютера в домені/робочій групі. |
| **`nomenclature_id`** | `INT` | DEFAULT NULL, FK | Зв'язок із малоцінними матеріалами (МШП). |
| **`status`** | `ENUM` | DEFAULT 'Працює' | Поточний стан: 'Працює', 'Потребує уваги', 'В ремонті', 'Списано'. |
| **`write_off_act_id`**| `INT` | DEFAULT NULL, FK | Посилання на акт списання для малоцінки. |

### Зв'язки та обмеження (Foreign Keys)
* `fk_assets_base_component` $\rightarrow$ `base_components`(`id`)
* `fk_assets_equipment` $\rightarrow$ `equipment`(`id`) `ON DELETE SET NULL ON UPDATE CASCADE`
* `fk_assets_holder` $\rightarrow$ `location_holders`(`id`) `ON DELETE SET NULL`
* `fk_assets_location` $\rightarrow$ `locations`(`id`) `ON DELETE SET NULL`
* `fk_assets_low_value_mat` $\rightarrow$ `low_value_materials`(`id`)
* `fk_assets_model` $\rightarrow$ `models_tz`(`id`)
* `fk_assets_parent_id` $\rightarrow$ `assets`(`id`) `ON DELETE SET NULL ON UPDATE CASCADE` (Рекурсивний зв'язок для вкладеності обладнання)
* `fk_assets_write_off_act_id` $\rightarrow$ `low_value_write_off_acts`(`id`)

---

## 2. Таблиця `attributes_dictionary` (Словник атрибутів)
Довідник динамічних характеристик обладнання (наприклад: "Об'єм пам'яті", "Частота процесора", "Тип роз'єму").

### Структура полів
| Назва поля | Тип даних | Налаштування | Опис призначення |
| :--- | :--- | :--- | :--- |
| **`id`** | `INT` | PK, Auto Increment, NOT NULL | Унікальний ID атрибута. |
| **`name`** | `VARCHAR(100)`| NOT NULL | Назва технічної характеристики. |

---

## 3. Таблиця `base_components` (Базові компоненти / Категорії деталей)
Класифікатор пристроїв та їх частин, що прив'язує їх до загальних категорій.

### Структура полів
| Назва поля | Тип даних | Налаштування | Опис призначення |
| :--- | :--- | :--- | :--- |
| **`id`** | `INT` | PK, Auto Increment, NOT NULL | Унікальний ID компонента. |
| **`component_name`** | `VARCHAR(100)`| NOT NULL | Назва компонента (наприклад: "Материнська плата", "Монітор"). |
| **`category_id`** | `INT` | DEFAULT NULL, FK | Посилання на глобальну категорію. |

### Зв'язки та обмеження (Foreign Keys)
* `fk_components_category_id` $\rightarrow$ `categories_tz`(`id`) `ON DELETE SET NULL ON UPDATE CASCADE`

---

## 4. Таблиця `brands_tz` (Бренди / Виробники)
Лінійний словник виробників технічних засобів.

### Структура полів
| Назва поля | Тип даних | Налаштування | Опис призначення |
| :--- | :--- | :--- | :--- |
| **`id`** | `INT` | PK, Auto Increment, NOT NULL | Унікальний ID бренду. |
| **`brandtz_name`** | `VARCHAR(45)` | NOT NULL, UNIQUE | Назва виробника (наприклад: "HP", "Asus", "Logitech"). |

---

## 5. Таблиця `categories_tz` (Категорії технічних засобів)
Глобальні категорії для розподілу майна за типами.

### Структура полів
| Назва поля | Тип даних | Налаштування | Опис призначення |
| :--- | :--- | :--- | :--- |
| **`id`** | `INT` | PK, Auto Increment, NOT NULL | Унікальний ID категорії. |
| **`category_name`** | `VARCHAR(45)` | NOT NULL, UNIQUE | Назва категорії (наприклад: "Комп'ютери", "Периферія"). |

---

## 6. Таблиця `computer_software` (Програмне забезпечення комп'ютерів)
Таблиця для обліку встановленого ПЗ на робочих місцях та контролю ліцензійної чистоти.

### Структура полів
| Назва поля | Тип даних | Налаштування | Опис призначення |
| :--- | :--- | :--- | :--- |
| **`id`** | `INT` | PK, Auto Increment, NOT NULL | Унікальний ID запису встановленого ПЗ. |
| **`computer_id`** | `INT` | NOT NULL, FK | ID комп'ютера з таблиці `assets`. |
| **`software_name`** | `ENUM` | NOT NULL | Контрольовані типи ПЗ: 'Windows', 'Office', 'ESET'. |
| **`version`** | `VARCHAR(50)` | NOT NULL | Версія програми (наприклад, '22H2', '2019'). |
| **`is_licensed`** | `TINYINT(1)` | NOT NULL, DEFAULT '0' | Прапор ліцензійності (0 — ні, 1 — так). |
| **`license_id`** | `INT` | DEFAULT NULL, FK | Посилання на куплену ліцензію з таблиці `licenses`. |

### Зв'язки та обмеження (Foreign Keys)
* Унікальний складений ключ `unique_pc_soft`: Забороняє дублювання однакового типу ПЗ на одному комп'ютері.
* `computer_software_assets_FK` $\rightarrow$ `assets`(`id`)
* `computer_software_licenses_FK` $\rightarrow$ `licenses`(`id`)

---

## 7. Таблиця `departments` (Підрозділи / Відділи)
Реєстр внутрішніх структурних підрозділів організації.

### Структура полів
| Назва поля | Тип даних | Налаштування | Опис призначення |
| :--- | :--- | :--- | :--- |
| **`id`** | `INT` | PK, Auto Increment, NOT NULL | Унікальний ID підрозділу. |
| **`name`** | `VARCHAR(150)`| NOT NULL | Повна назва відділу/управління. |

---

## 8. Таблиця `employee` (Співробітники)
Облікові дані працівників, які використовують техніку або є матеріально відповідальними.

### Структура полів
| Назва поля | Тип даних | Налаштування | Опис призначення |
| :--- | :--- | :--- | :--- |
| **`id`** | `INT` | PK, Auto Increment, NOT NULL | Унікальний ID співробітника. |
| **`last_name`** | `VARCHAR(100)`| NOT NULL | Прізвище. |
| **`first_name`** | `VARCHAR(100)`| NOT NULL | Ім'я. |
| **`middle_name`** | `VARCHAR(100)`| DEFAULT NULL | По батькові. |
| **`position`** | `VARCHAR(150)`| DEFAULT NULL | Посада співробітника. |
| **`status`** | `ENUM` | DEFAULT 'Працює' | Статус кадрового стану: 'Працює', 'Звільнений'. |
| **`department_id`** | `INT` | DEFAULT NULL, FK | Департамент, до якого прикріплений працівник. |

### Зв'язки та обмеження (Foreign Keys)
* `fk_employee_department_id` $\rightarrow$ `departments`(`id`) `ON DELETE SET NULL ON UPDATE CASCADE`

---

## 9. Таблиця `employee_phones` (Телефони співробітників)
Контактні номери телефонів працівників.

### Структура полів
| Назва поля | Тип даних | Налаштування | Опис призначення |
| :--- | :--- | :--- | :--- |
| **`id`** | `INT` | PK, Auto Increment, NOT NULL | Унікальний ID телефонного номера. |
| **`employee_id`** | `INT` | NOT NULL, FK | ID працівника з таблиці `employee`. |
| **`phone_number`** | `VARCHAR(20)` | NOT NULL | Номер телефону. |
| **`phone_type`** | `ENUM` | DEFAULT 'Робочий' | Категорія номера: 'Робочий', 'Особистий', 'Додатковий'. |

### Зв'язки та обмеження (Foreign Keys)
* `fk_phones_employee_id` $\rightarrow$ `employee`(`id`) `ON DELETE CASCADE ON UPDATE CASCADE` (При видаленні картки працівника його телефони видаляються автоматично).

---

## 10. Таблиця `equipment` (Бухгалтерське обладнання / Основні засоби)
Реєстр офіційно взятого на баланс інвентарного майна організації.

### Структура полів
| Назва поля | Тип даних | Налаштування | Опис призначення |
| :--- | :--- | :--- | :--- |
| **`id`** | `INT` | PK, Auto Increment, NOT NULL | Унікальний системний ID інвентарної одиниці. |
| **`inv_number`** | `VARCHAR(45)` | DEFAULT NULL | Офіційний бухгалтерський інвентарний номер. |
| **`account_name`** | `VARCHAR(45)` | DEFAULT NULL | Балансова назва об'єкта обліку. |
| **`buy_price`** | `DECIMAL(10,2)`| DEFAULT NULL | Вартість придбання техніки. |
| **`purchase_id`** | `INT` | DEFAULT NULL, FK | Зв'язок із договором закупівлі з таблиці `purchases`. |
| **`status`** | `ENUM` | DEFAULT NULL | Господарський статус: 'в експлуатації', 'в аренді', 'списано'. |
| **`retirement_act_id`**| `INT` | DEFAULT NULL, FK | Номер акта ліквідації / списання з балансу. |
| **`notes`** | `VARCHAR(45)` | DEFAULT NULL | Примітки бухгалтерії. |

### Зв'язки та обмеження (Foreign Keys)
* `fk_equipment_purchase` $\rightarrow$ `purchases`(`id`) `ON DELETE SET NULL ON UPDATE CASCADE`
* `fk_equipment_write_off` $\rightarrow$ `equipment_retirement_acts`(`id`) `ON DELETE SET NULL ON UPDATE CASCADE`

---

## 11. Таблиця `equipment_retirement_acts` (Акти списання основних засобів)
Документи, що підтверджують зняття великого обладнання з балансу установи.

### Структура полів
| Назва поля | Тип даних | Налаштування | Опис призначення |
| :--- | :--- | :--- | :--- |
| **`id`** | `INT` | PK, Auto Increment, NOT NULL | Унікальний ID акта. |
| **`act_number`** | `VARCHAR(100)`| DEFAULT NULL | Офіційний номер акта ліквідації основних засобів. |
| **`act_date`** | `DATE` | DEFAULT NULL | Дата затвердження акта. |
| **`reason`** | `TEXT` | DEFAULT NULL | Причина ліквідації (наприклад: "фізичний знос", "поломка без можливості ремонту"). |

---

## 12. Таблиця `item_properties` (Динамічні властивості одиниць обліку)
Пов'язує конкретні екземпляри техніки (`assets`) або малоцінки (`low_value_materials`) з технічними характеристиками зі словника атрибутів.

### Структура полів
| Назва поля | Тип даних | Налаштування | Опис призначення |
| :--- | :--- | :--- | :--- |
| **`id`** | `INT` | PK, Auto Increment, NOT NULL | Унікальний ID запису властивості. |
| **`asset_id`** | `INT` | DEFAULT NULL, FK | Зв'язок із таблицею `assets`. |
| **`nomenclature_id`** | `INT` | DEFAULT NULL, FK | Зв'язок із таблицею `low_value_materials`. |
| **`attribute_id`** | `INT` | NOT NULL, FK | ID характеристики з `attributes_dictionary`. |
| **`attr_value`** | `VARCHAR(255)`| DEFAULT NULL | Текстове значення характеристики (наприклад, '16 GB', 'Intel Core i5'). |

### Зв'язки та обмеження (Foreign Keys)
* `fk_nomenklature` $\rightarrow$ `low_value_materials`(`id`)
* `fk_properties_asset_id` $\rightarrow$ `assets`(`id`) `ON DELETE CASCADE` (При видаленні активу його технічні характеристики видаляються автоматично).
* `item_properties_ibfk_1` $\rightarrow$ `attributes_dictionary`(`id`)
## 13. Таблиця `licenses` (Ліцензії)
Реєстр ліцензій для програмного забезпечення.

### Структура полів
| Назва поля | Тип даних | Налаштування | Опис призначення |
| :--- | :--- | :--- | :--- |
| **`id`** | `INT` | PK, Auto Increment, NOT NULL | Унікальний системний ID ліцензії. |
| **`vendor_id`** | `INT` | DEFAULT NULL | ID виробника/постачальника ліцензії. |
| **`license_name`** | `VARCHAR(150)`| NOT NULL | Назва ліцензії (наприклад, назва пакету чи програми). |
| **`license_type`** | `VARCHAR(50)` | DEFAULT NULL | Тип ліцензії (наприклад: OEM, Retail, корпоративна). |
| **`purchase_date`**| `DATE` | DEFAULT NULL | Дата придбання ліцензії. |

---

## 14. Таблиця `location_holders` (Утримувачі локацій)
Зв'язує відповідальних осіб (співробітників або сторонні організації) з майном.

### Структура полів
| Назва поля | Тип даних | Налаштування | Опис призначення |
| :--- | :--- | :--- | :--- |
| **`id`** | `INT` | PK, Auto Increment, NOT NULL | Унікальний ID запису утримувача. |
| **`employee_id`** | `INT` | DEFAULT NULL, FK | ID співробітника з таблиці `employee`. |
| **`organization_id`**| `INT` | DEFAULT NULL, FK | ID організації з таблиці `organizations`. |

### Зв'язки та обмеження (Foreign Keys)
* `fk_holders_employee` $\rightarrow$ `employee`(`id`) `ON DELETE CASCADE`
* `fk_holders_organization` $\rightarrow$ `organizations`(`id`) `ON DELETE CASCADE`

---

## 15. Таблиця `locations` (Фізичні локації)
Список кабінетів чи приміщень, де розміщується техніка.

### Структура полів
| Назва поля | Тип даних | Налаштування | Опис призначення |
| :--- | :--- | :--- | :--- |
| **`id`** | `INT` | PK, Auto Increment, NOT NULL | Унікальний ID приміщення. |
| **`room_number`** | `VARCHAR(100)`| NOT NULL | Номер або назва кабінету / кімнати. |

---

## 16. Таблиця `low_value_materials` (Малоцінні матеріали)
Облік малоцінних та швидкозношуваних предметів (МШП).

### Структура полів
| Назва поля | Тип даних | Налаштування | Опис призначення |
| :--- | :--- | :--- | :--- |
| **`id`** | `INT` | PK, Auto Increment, NOT NULL | Унікальний системний ID малоцінки. |
| **`material_account_name`**| `VARCHAR(300)`| DEFAULT NULL | Облікова назва матеріалу. |
| **`price`** | `DECIMAL(10,2)`| DEFAULT NULL | Ціна матеріалу. |
| **`count`** | `INT` | DEFAULT NULL | Кількість одиниць. |
| **`nomenklature_number`**| `VARCHAR(45)`| DEFAULT NULL | Номенклатурний номер матеріалу. |
| **`contract_id`** | `INT` | DEFAULT NULL, FK | ID договору закупівлі з таблиці `purchases`. |

### Зв'язки та обмеження (Foreign Keys)
* `fk_materials_contract_id` $\rightarrow$ `purchases`(`id`) `ON DELETE SET NULL ON UPDATE CASCADE`

---

## 17. Таблиця `low_value_write_off_acts` (Акти списання малоцінки)
Документи про списання малоцінних матеріалів.

### Структура полів
| Назва поля | Тип даних | Налаштування | Опис призначення |
| :--- | :--- | :--- | :--- |
| **`id`** | `INT` | PK, Auto Increment, NOT NULL | Унікальний системний ID акта. |
| **`act_number`** | `VARCHAR(45)` | DEFAULT NULL | Номер акта списання малоцінки. |
| **`act_date`** | `DATE` | DEFAULT NULL | Дата підписання/затвердження акта. |

---

## 18. Таблиця `models_tz` (Моделі технічних засобів)
Довідник моделей обладнання, прив'язаних до брендів.

### Структура полів
| Назва поля | Тип даних | Налаштування | Опис призначення |
| :--- | :--- | :--- | :--- |
| **`id`** | `INT` | PK, Auto Increment, NOT NULL | Унікальний системний ID моделі. |
| **`brand_id`** | `INT` | DEFAULT NULL, FK | ID бренду з таблиці `brands_tz`. |
| **`model_name`** | `VARCHAR(45)` | DEFAULT NULL | Назва моделі технічного засобу. |

### Зв'язки та обмеження (Foreign Keys)
* `fk_models_brand_id` $\rightarrow$ `brands_tz`(`id`) `ON DELETE CASCADE ON UPDATE CASCADE`

---

## 19. Таблиця `movements` (Переміщення активів)
Журнал логування переміщень техніки чи обладнання між утримувачами та відповідальними особами.

### Структура полів
| Назва поля | Тип даних | Налаштування | Опис призначення |
| :--- | :--- | :--- | :--- |
| **`id`** | `INT` | PK, Auto Increment, NOT NULL | Унікальний ID запису про переміщення. |
| **`equip_id`** | `INT` | DEFAULT NULL, FK | ID обладнання з таблиці `equipment`. |
| **`asset_id`** | `INT` | DEFAULT NULL, FK | ID активу з таблиці `assets`. |
| **`from_holder_id`** | `INT` | DEFAULT NULL, FK | ID попереднього утримувача з `location_holders`. |
| **`to_holder_id`** | `INT` | DEFAULT NULL, FK | ID нового утримувача з `location_holders`. |
| **`employee_id`** | `INT` | DEFAULT NULL, FK | ID співробітника, що зафіксував чи виконав дію. |
| **`action_date`** | `DATETIME` | DEFAULT CURRENT_TIMESTAMP | Дата та час проведення операції переміщення. |

### Зв'язки та обмеження (Foreign Keys)
* `fk_movements_asset_id` $\rightarrow$ `assets`(`id`) `ON DELETE SET NULL`
* `fk_movements_employee` $\rightarrow$ `employee`(`id`)
* `fk_movements_equip_id` $\rightarrow$ `equipment`(`id`) `ON DELETE SET NULL ON UPDATE CASCADE`
* `fk_movements_from_holder` $\rightarrow$ `location_holders`(`id`) `ON DELETE RESTRICT ON UPDATE CASCADE`
* `fk_movements_to_holder` $\rightarrow$ `location_holders`(`id`)

---

## 20. Таблиця `organizations` (Організації)
Довідник сторонніх або внутрішніх організацій.

### Структура полів
| Назва поля | Тип даних | Налаштування | Опис призначення |
| :--- | :--- | :--- | :--- |
| **`id`** | `INT` | PK, Auto Increment, NOT NULL | Унікальний системний ID організації. |
| **`org_name`** | `VARCHAR(255)`| NOT NULL | Назва організації. |
| **`org_type`** | `VARCHAR(100)`| DEFAULT 'Стороння' | Тип організації (за замовчуванням 'Стороння'). |

---

## 21. Таблиця `purchases` (Закупівлі)
Договори та контракти, за якими надходило майно.

### Структура полів
| Назва поля | Тип даних | Налаштування | Опис призначення |
| :--- | :--- | :--- | :--- |
| **`id`** | `INT` | PK, Auto Increment, NOT NULL | Унікальний системний ID договору. |
| **`contract_number`** | `VARCHAR(100)`| NOT NULL | Офіційний номер договору (або 'Б/Н'). |
| **`contract_date`** | `DATE` | DEFAULT NULL | Дата підписання договору. |
| **`supplier_id`** | `INT` | DEFAULT NULL, FK | ID постачальника з таблиці `suppliers`. |
| **`contract_link`** | `VARCHAR(100)`| DEFAULT NULL | Шлях або лінк на документ договору. |

### Зв'язки та обмеження (Foreign Keys)
* `fk_purchases_supplier_id` $\rightarrow$ `suppliers`(`id`) `ON UPDATE CASCADE`

---

## 22. Таблиця `repairs` (Ремонти)
Облік технічних засобів, які здавалися на сервісне обслуговування.

### Структура полів
| Назва поля | Тип даних | Налаштування | Опис призначення |
| :--- | :--- | :--- | :--- |
| **`id`** | `INT` | PK, Auto Increment, NOT NULL | Унікальний ID запису про ремонт. |
| **`assets_id`** | `INT` | NOT NULL, FK | ID активу з таблиці `assets`. |
| **`sent_date`** | `DATE` | NOT NULL | Дата відправки пристрою в ремонт. |
| **`return_date`** | `DATE` | DEFAULT NULL | Дата повернення з ремонту. |
| **`issue_description`**| `TEXT` | DEFAULT NULL | Опис несправності чи поломки. |
| **`status`** | `ENUM` | DEFAULT 'В ремонті' | Статус ремонту: 'В ремонті', 'Відремонтовано' або 'Неможливо відремонтувати'. |

### Зв'язки та обмеження (Foreign Keys)
* `fk_repairs_assets_id` $\rightarrow$ `assets`(`id`) `ON DELETE RESTRICT ON UPDATE CASCADE`

---

## 23. Таблиця `supplier_types` (Типи постачальників)
Довідник організаційно-правових форм постачальників (ТОВ, ФОП тощо).

### Структура полів
| Назва поля | Тип даних | Налаштування | Опис призначення |
| :--- | :--- | :--- | :--- |
| **`id`** | `INT` | PK, Auto Increment, NOT NULL | Унікальний ID типу. |
| **`type_name`** | `VARCHAR(20)` | NOT NULL | Скорочена назва (наприклад: ТОВ, ФОП, ПП, ДП, АТ). |

---

## 24. Таблиця `suppliers` (Постачальники)
Реєстр контрагентів та постачальників, у яких купувалося майно.

### Структура полів
| Назва поля | Тип даних | Налаштування | Опис призначення |
| :--- | :--- | :--- | :--- |
| **`id`** | `INT` | PK, Auto Increment, NOT NULL | Унікальний системний ID постачальника. |
| **`supplier_name`** | `VARCHAR(255)`| NOT NULL | Назва компанії або ПІБ фізичної особи. |
| **`supplier_type_id`**| `INT` | DEFAULT NULL, FK | ID типу форми власності з `supplier_types`. |
| **`tax_code`** | `VARCHAR(12)` | DEFAULT NULL | Код ЄДРПОУ або ІПН постачальника. |

### Зв'язки та обмеження (Foreign Keys)
* `fk_suppliers_types` $\rightarrow$ `supplier_types`(`id`)