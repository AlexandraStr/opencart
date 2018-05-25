<?php
// Heading
$_['heading_title']                         = 'Export / Import';

// Text
$_['text_success'] = 'Успех: вы успешно импортировали свои данные!';
$_['text_success_settings'] = 'Успех: вы успешно обновили настройки для инструмента «Экспорт / Импорт»! ';
$_['text_export_type_category'] = 'Категории (включая данные категорий и фильтры)';
$_['text_export_type_category_old'] = 'Категории';
$_['text_export_type_product'] = 'Продукты (включая данные о продукте, опции, скидки, скидки, награды, атрибуты и фильтры)';
$_['text_export_type_product_old'] = 'Продукты (включая данные о продуктах, опционы, скидки, скидки, награды и атрибуты)';
$_['text_export_type_option'] = 'Определения опций';
$_['text_export_type_attribute'] = 'Определения атрибутов';
$_['text_export_type_filter'] = 'Определения фильтров';
$_['text_export_type_customer'] = 'Клиенты';
$_['text_yes'] = 'Да';
$_['text_no'] = 'Нет';
$_['text_nochange'] = 'Данные сервера не изменены.';
$_['text_log_details'] = 'См. также  Журнал ошибок для более подробной информации.';
$_['text_log_details_2_0_x'] = 'См. также  Инструменты ; Журнал ошибок для более подробной информации.';
$_['text_log_details_2_1_x'] = 'См. также System ; Инструменты ; Журнал ошибок для более подробной информации.';
$_['text_loading_notifications'] = 'Получение сообщений';
$_['text_retry'] = 'Повторить';

// Entry
$_['entry_import'] = 'Импорт из файла электронной таблицы XLS, XLSX или ODS';
$_['entry_export'] = 'Экспортировать запрошенные данные в файл электронной таблицы XLSX.';
$_['entry_export_type'] = 'Выберите, какие данные вы хотите экспортировать:';
$_['entry_range_type'] = 'Выберите диапазон данных, который вы хотите экспортировать:';
$_['entry_start_id'] = 'Начальный идентификатор:';
$_['entry_start_index'] = 'Подсчет за пакет:';
$_['entry_end_id'] = 'Конечный id:';
$_['entry_end_index'] = 'Номер партии:';
$_['entry_incremental'] = 'Использовать инкрементный импорт';
$_['entry_upload'] = 'Файл для загрузки';
$_['entry_settings_use_option_id'] = 'Использовать <em> option_id </em> вместо <em> имя опции </em> в листах  ProductOptions  и  ProductOptionValues ​​';
$_['entry_settings_use_option_value_id'] = 'Использовать <em> option_value_id </em> вместо <em> имя_параметра </em> в листе  ProductOptionValues ​​';
$_['entry_settings_use_attribute_group_id'] = 'Использовать <em> attribute_group_id </em> вместо <em> имя_файла_группы </em> в листе ProductAttributes ';
$_['entry_settings_use_attribute_id'] = 'Использовать <em> attribute_id </em> вместо <em> имя атрибута </ em> в рабочем листе  ProductAttributes ';
$_['entry_settings_use_filter_group_id'] = 'Использовать <em> filter_group_id </em> вместо <em> имя_фильтра_группы </em> в листах  ProductFilters CategoryFilters ';
$_['entry_settings_use_filter_id'] = 'Использовать <em> filter_id </em> вместо <em> имя фильтра </em> в листах ProductFilters и  CategoryFilters ';
$_['entry_settings_use_export_cache'] = 'Использовать кеш phpTemp для большого экспорта (будет немного медленнее)';
$_['entry_settings_use_import_cache'] = 'Использовать кеш phpTemp для больших импортов (будет немного медленнее)';

// Error
$_['error_permission'] = 'Предупреждение: у вас нет разрешения на изменение экспорта / импорта!';
$_['error_upload'] = 'Загруженный файл электронной таблицы имеет ошибки проверки!';
$_['error_worksheets'] = 'Экспорт / Импорт: Недопустимые имена рабочих листов';
$_['error_categories_header'] = 'Экспорт / импорт: недопустимый заголовок на листе Категории';
$_['error_category_filters_header'] = 'Экспорт / импорт: недопустимый заголовок в листе CategoryFilters';
$_['error_category_seo_keywords_header'] = 'Экспорт / импорт: недопустимый заголовок на листе CategorySEOKeywords';
$_['error_products_header'] = 'Экспорт / импорт: недопустимый заголовок в листе Продукты';
$_['error_additional_images_header'] = 'Экспорт / Импорт: недопустимый заголовок в листе AdditionalImages';
$_['error_specials_header'] = 'Экспорт / импорт: недопустимый заголовок в листе Specials';
$_['error_discounts_header'] = 'Экспорт / импорт: недопустимый заголовок в рабочем листе Скидки';
$_['error_rewards_header'] = 'Экспорт / Импорт: недопустимый заголовок в листе Награды';
$_['error_product_options_header'] = 'Экспорт / импорт: недопустимый заголовок в листе ProductOptions';
$_['error_product_option_values_header'] = 'Экспорт / импорт: недопустимый заголовок в листе ProductOptionValues';
$_['error_product_attributes_header'] = 'Экспорт / импорт: недопустимый заголовок в рабочем листе ProductAttributes';
$_['error_product_filters_header'] = 'Экспорт / импорт: недопустимый заголовок в листе ProductFilters';
$_['error_product_seo_keywords_header'] = 'Экспорт / импорт: недопустимый заголовок на листе ProductSEOKeywords';
$_['error_options_header'] = 'Экспорт / импорт: недопустимый заголовок в листе Параметры';
$_['error_option_values_header'] = 'Экспорт / импорт: недопустимый заголовок на листе OptionValues';
$_['error_attribute_groups_header'] = 'Экспорт / импорт: недопустимый заголовок в листе AttributeGroups';
$_['error_attributes_header'] = 'Экспорт / импорт: недопустимый заголовок в листе атрибутов';
$_['error_filter_groups_header'] = 'Экспорт / импорт: недопустимый заголовок в листе FilterGroups';
$_['error_filters_header'] = 'Экспорт / импорт: недопустимый заголовок на листе фильтров';
$_['error_customers_header'] = 'Экспорт / импорт: недопустимый заголовок в листе клиентов';
$_['error_addresses_header'] = 'Экспорт / импорт: недопустимый заголовок на листе Адреса';
$_['error_product_options'] = 'Экспортировать / импортировать: лист отсутствующих продуктов или лист продуктов, который не указан перед ProductOptions';
$_['error_product_option_values'] = 'Экспортировать / импортировать: лист с отсутствующими продуктами или лист продуктов, который не указан перед ProductOptionValues';
$_['error_product_option_values_2'] = 'Экспорт / Импорт: Отсутствует рабочий лист ProductOptions или лист ProductOptions, не указанный в ProductOptionValues';
$_['error_product_option_values_3'] = 'Экспорт / импорт: рабочий лист ProductOptionValues ​​также ожидается после листа ProductOptions';
$_['error_additional_images'] = 'Экспортировать / импортировать: лист отсутствующих продуктов или лист продуктов, который не указан перед дополнительными изображениями';
$_['error_specials'] = 'Экспортировать / импортировать: лист с отсутствующими продуктами или листок продуктов, не указанный перед выпуском Specials';
$_['error_discounts'] = 'Экспорт / Импорт: лист с отсутствующими продуктами или лист с продуктом, который не указан перед скидками';
$_['error_rewards'] = 'Экспортировать / импортировать: лист с отсутствующими продуктами, или лист продуктов, который не указан перед вознаграждением';
$_['error_product_attributes'] = 'Экспортировать / импортировать: лист отсутствующих продуктов или лист продуктов, который не указан перед ProductAttributes';
$_['error_attributes'] = 'Экспортировать / импортировать: лист отсутствующих атрибутов или таблицу атрибутов, не указанную перед атрибутами';
$_['error_attributes_2'] = 'Экспорт / Импорт: лист атрибутов также ожидается после листа таблицы AttributeGroups';
$_['error_category_filters'] = 'Экспортировать / Импортировать: лист отсутствующих категорий или листы категорий, не указанные перед CategoryFilters';
$_['error_category_seo_keywords'] = 'Экспортировать / импортировать: лист отсутствующих категорий или рабочий лист категорий, не указанный перед словами CategorySEOKeywords';
$_['error_product_filters'] = 'Экспортировать / импортировать: лист отсутствующих продуктов или лист продуктов, который не указан перед ProductFilters';
$_['error_product_seo_keywords'] = 'Экспортировать / импортировать: лист отсутствующих продуктов или лист продуктов, который не указан перед ProductSEOKeywords';
$_['error_filters'] = 'Экспортировать / Импортировать: лист отсутствующих файлов FilterGroups или лист FilterGroups, не указанный перед фильтрами';
$_['error_filters_2'] = 'Экспорт / Импорт: рабочий лист фильтров также ожидается после листа FilterGroups';
$_['error_option_values'] = 'Экспортировать / импортировать: лист отсутствующих параметров или лист параметров, не указанный перед OptionValues';
$_['error_option_values_2'] = 'Экспорт / Импорт: рабочий лист OptionValues ​​также ожидается после листа параметров';
$_['error_post_max_size'] = 'Размер файла больше %1 (см. настройку PHP  post_max_size ) ';
$_['error_upload_max_filesize'] = 'Размер файла больше% 1 (см. параметр PHP  upload_max_filesize ) ';
$_['error_select_file'] = 'Выберите файл перед нажатием  Импорт ';
$_['error_id_no_data'] = 'Нет данных между start-id и end-id.';
$_['error_page_no_data'] = 'Больше данных.';
$_['error_param_not_number'] = 'Значения для диапазона данных должны быть целыми числами.';
$_['error_upload_name'] = 'Отсутствует имя файла для загрузки';
$_['error_upload_ext'] = 'Загруженный файл не имеет одного из расширений имени файла .xls . xlsx  ods  это может быть не файл электронной таблицы!';
$_['error_notifications'] = 'Не удалось загрузить сообщения из MHCCORP.COM.';
$_['error_no_news'] = 'Нет сообщений';
$_['error_batch_number'] = 'Номер партии должен быть больше 0';
$_['error_min_item_id'] = 'Идентификатор запуска должен быть больше 0';
$_['error_option_name'] = 'Опция %1 определяется несколько раз! <br />';
$_['error_option_name'] = 'На вкладке «Настройки» активируйте следующее: <br /> ';
$_['error_option_name'] = 'Использовать <em> option_id </em> вместо <em> имя опции </em> в листах ProductOptions и ProductOptionValues';
$_['error_option_value_name']= 'Значение параметра % 1  определено несколько раз в пределах своей опции! <br/> ';
$_['error_option_value_name'] = 'На вкладке «Настройки» активируйте следующее: <br/> ';
$_['error_option_value_name'] = 'Использовать <em> option_value_id </ em> вместо <em> имя_параметра </em> на листе« ProductOptionValues ​​';
$_['error_attribute_group_name'] = 'AttributeGroup %1  определяется несколько раз! <br/>';
$_['error_attribute_group_name']= 'На вкладке «Настройки» активируйте следующее: <br/> ';
$_['error_attribute_group_name']= 'Использовать <em> attribute_group_id </em> вместо <em> имя_группы атрибутов </em> в листах ProductAttributes ';
$_['error_attribute_name'] = 'Атрибут  %1  определяется несколько раз в пределах своей группы атрибутов! <br/>';
$_['error_attribute_name'] = 'На вкладке «Настройки» активируйте следующее: <br />';
$_['error_attribute_name'] = 'Использовать <em> attribute_id </em> вместо <em> имя атрибута </em> в листе« ProductAttributes ';
$_['error_filter_group_name'] = 'FilterGroup  %1  определяется несколько раз! <br />';
$_['error_filter_group_name'] = 'На вкладке «Настройки» активируйте следующее: <br /> ';
$_['error_filter_group_name'] = 'Использовать <em> filter_group_id </em> вместо <em> имя_фильтра_группы </em> в листах ProductLilters ';
$_['error_filter_name'] = 'Фильтр  %1  определяется несколько раз в пределах своей группы фильтров! <br/>';
$_['error_filter_name'] = 'На вкладке «Настройки» активируйте следующее: <br /> ';
$_['error_filter_name'] = 'Использовать <em> filter_id </em> вместо <em> имя фильтра </em> на листе« ProductFilters ';
$_['error_incremental'] = 'Отсутствует параметр incremental (Да или Нет) для импорта';


// Tabs
$_['tab_import']                            = 'Импорт';
$_['tab_export']                            = 'Экспорт';
$_['tab_settings']                          = 'Настройки';

// Button labels
$_['button_import']                         = 'Импорт';
$_['button_export']                         = 'Экспорт';
$_['button_settings']                       = 'Обновление настроек';
$_['button_export_id']                      = 'По дыапазону id';
$_['button_export_page']                    = 'По партиям';

// Help
$_['help_range_type']                       = '(Необязательно, оставляйте пустым, если не нужно.)';
$_['help_incremental_yes']                  = '(Обновление и / или добавление данных)';
$_['help_incremental_no']                   = '(Удалить все старые данные перед импортом)';
$_['help_import']                           = 'В таблице могут быть категории, продукты, определения атрибутов, определения опций или определения фильтров. ';
$_['help_import_old']                       = 'В таблице могут быть категории, продукты, определения атрибутов или определения опций. ';
$_['help_format']                           = 'Сначала выполните экспорт, чтобы увидеть точный формат рабочих листов.!';
?>