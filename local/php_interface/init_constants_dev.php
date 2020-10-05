<?php
define("IBLOCK_CONTRACTORS", 1);
define("IBLOCK_SERVICES", 3);
define("IBLOCK_ARTICLES", 2);
define("IBLOCK_ARTICLES_COMMENTS", 7);
// define("IBLOCK_ARTICLES_PROPOSALS", 15);
define("IBLOCK_ARTICLES_TYPES", 9);
define("IBLOCK_REVIEWS", 4);
define("IBLOCK_BACKOFFICE_NEWS", 8);
define("IBLOCK_BACKOFFICE_FAQ", 6);
define("IBLOCK_BACKOFFICE_TICKETS", 16);
define("IBLOCK_RAEK_REQUESTS", 10);
define("IBLOCK_RAEK_VERIFICATIONS", 11);
define("IBLOCK_EMASTER_REQUESTS", 14);
define("IBLOCK_SERVICE", 17);
define("IBLOCK_SERVICE_TYPES", 19);
define("IBLOCK_EVENTS", 18);
define("IBLOCK_EVENTS_TYPES", 20);
define("IBLOCK_SEO_CONTENT", 21);
define("IBLOCK_PARTNERS", 22);
define("IBLOCK_CONTRACTORS_WORKS", 23);
define("IBLOCK_ORDERS", 24);
define("IBLOCK_DISTRIBUTORS", 28);
define("IBLOCK_VENDORS", 31);
define("IBLOCK_CLIENTS", 29);
define("IBLOCK_EDUCATIONS", 25);
define("IBLOCK_EDUCATIONS_TYPES", 26);
define("IBLOCK_EDUCATIONS_THEMES", 27);
define("IBLOCK_SALES", 30);
define("IBLOCK_PARTNERS_OFFERS", 32);
define("IBLOCK_PARTNERS_OFFERS_TYPES", 33);
define("IBLOCK_PARTNERS_OFFERS_ORDERS", 34);

define("HLBLOCK_CONTRACTOR_TO_SERVICE", 1);
define("HLBLOCK_COURSES", 5);
define("HLBLOCK_EDUCATIONS", 4);
define("HLBLOCK_JOBS", 3);
define("HLBLOCK_SKILLS", 9);
define("HLBLOCK_LANGUAGES", 11);
define("HLBLOCK_LANGUAGES_LIST", 12);
define("HLBLOCK_LANGUAGES_LEVELS", 13);
define("HLBLOCK_LIKES", 10);
define("HLBLOCK_BANNERS", 14);
define("HLBLOCK_FORM_BANNERS", 15);
define("HLBLOCK_VIEWS_DETAIL", 16);
define("HLBLOCK_VIEWS_CONTACTS", 17);
define("HLBLOCK_CITIES", 18);
define("HLBLOCK_LOCATIONS", 19);
define("HLBLOCK_NOTIFICATIONS", 20);
define("HLBLOCK_INTEGRATION_PARTNERS", 22);
define("HLBLOCK_CONTRACTOR_POINTS", 23);
define("HLBLOCK_CONTACTOR_ORDER", 24);
define("HLBLOCK_NEW_ORDERS", 25);
define("HLBLOCK_CONTRACTOR_POINTS_HISTORY", 26);

define("USER_GROUP_CLIENTS", 7);
define("USER_GROUP_CONTRACTORS", 8);
define("USER_GROUP_DISTRIBUTORS", 9);
define("USER_GROUP_VENDORS", 10);

define("PROPERTY_EVENTS_LOCATIONS", 77);
define("PROPERTY_EDUCATIONS_LOCATIONS", 105);

define("PROPERTY_ORDERS_CITY_FIAS", 100);

define("PROPERTY_CONTRACTORS_LOCATIONS", 55);
define("PROPERTY_CONTRACTORS_TYPE", 4);
define("PROPERTY_CONTRACTORS_SKILL", 8);
define("PROPERTY_CONTRACTORS_ROOM", 35);
define("PROPERTY_CONTRACTORS_USER", 1);
define("PROPERTY_CONTRACTORS_IS_DRAFT", 56);
define("PROPERTY_REVIEWS_USER", 2);
define("PROPERTY_REVIEWS_NOTIFICATION_SENT", 57);
define("PROPERTY_REVIEWS_AUTHOR", 28);
define("PROPERTY_ARTICLES_TYPE", 26);
define("PROPERTY_ARTICLES_TAGS", 18);

define("ENUM_VALUE_CONTRACTORS_TYPE_INDIVIDUAL", 1); // Значание списка "Тип" (Исполнители): Физическое лицо
define("ENUM_VALUE_CONTRACTORS_TYPE_LEGAL", 2); // Значание списка "Тип" (Исполнители): Юридическое лицо
define("ENUM_VALUE_CONTRACTORS_IS_DRAFT", 12); // Значание списка "Черновик" (Исполнители): Да
define("ENUM_VALUE_DISTRIBUTORS_IS_DRAFT", 40); // Значание списка "Черновик" (Дистрибьютор): Да
define("ENUM_VALUE_CLIENTS_IS_DRAFT", 44); // Значание списка "Черновик" (Клиенты): Да
define("ENUM_VALUE_REVIEWS_VIEWED", 11); // Значание списка "Просмотрен исполнителем" (Отзывы): Да
define("ENUM_VALUE_REVIEWS_NOTIFICATION_SENT", 13); // Значание списка "Уведомление отправлено" (Отзывы): Да
define("ENUM_VALUE_COMMENTS_VIEWED", 10); // Значание списка "Просмотрен автором" (Комментарии к статьям): Да
define("ENUM_VALUE_EVENTS_ARCHIVE", 41); // Значание списка "В архиве" : Да
define("ENUM_VALUE_EDUCATIONS_ARCHIVE", 42); // Значание списка "В архиве" : Да
define("ENUM_VALUE_SALES_ARCHIVE", 43); // Значание списка "В архиве" : Да
define("ENUM_VALUE_VENDORS_ARCHIVE", 44); // Значание списка "Черновик" (Вендоры): Да



define("ENUM_VALUE_ORDERS_STATUS_NEW", 31); // Значание списка "Статус" (Заявки): Новая
define("ENUM_VALUE_ORDERS_STATUS_INWORK", 32); // Значание списка "Статус" (Заявки): В работе
define("ENUM_VALUE_ORDERS_STATUS_COMPLETED", 33); // Значание списка "Статус" (Заявки): Завершён
define("ENUM_VALUE_ORDERS_STATUS_CANCELED", 34); // Значание списка "Статус" (Заявки): Отклонена
define("ENUM_VALUE_ORDERS_STATUS_CANCELED_COMPLAINT", 35); // Значание списка "Статус" (Заявки): Отклонена с жалобой
define("ENUM_VALUE_ORDERS_STATUS_SKIPPED", 36); // Значание списка "Статус" (Заявки): Пропущена
define("ENUM_VALUE_ORDERS_STATUS_STAY_REVIEW", 46); // Значание списка "Статус" (Заявки): Оставить отзыв

define("ENUM_VALUE_ORDERS_REASONS_NO_CLIENT", 47); // Значание списка "Причины отказа" (Заявки): Клиент не отвечает
define("ENUM_VALUE_ORDERS_REASONS_NO_CONDITION", 48); // Значание списка "Причины отказа" (Заявки): Условия не подошли

define("PATH_MARKETPLACE", "/e/");
define("PATH_ARTICLES", "/articles/");
define("PATH_BACKOFFICE_ARTICLES", "/backoffice/articles");
define("PATH_SERVICES", "/services/");

define("PATH_TERMS", "/terms/");

define("CONTRACTORS_PAGE_SIZE", 5);
define("REVIEWS_PAGE_SIZE", 5);
define("BACKOFFICE_REVIEWS_PAGE_SIZE", 20);
define("ARTICLES_PAGE_SIZE", 5);
define("ARTICLE_COMMENTS_PAGE_SIZE", 5);

define("ARTICLES_PROPERTY_TYPE_VIDEO", 21);

define("CONTRACTORS_FILTER_HINTS", [
    [
        "value" => "Замена проводки",
    ],
    [
        "value" => "Устранение неполадок",
    ],
    [
        "value" => "Создание проекта электросети",
    ],
]);

define("ARTICLE_COMMENTS_MAX_DEPTH", 5);
define("COMMENTS_DISLIKES_THRESHOLD", 11);

define("PREFERRED_CITIES", [
    "Москва" => "0c5b2444-70a0-4932-980c-b4dc0d3f02b5",
    "Санкт-Петербург" => "c2deb16a-0330-4f05-821f-1d09c93331e6",
    "Астрахань" => "a101dd8b-3aee-4bda-9c61-9df106f145ff",
    "Барнаул" => "d13945a8-7017-46ab-b1e6-ede1e89317ad",
    "Воронеж" => "5bf5ddff-6353-4a3d-80c4-6fb27f00c6c1",
    "Владивосток" => "7b6de6a5-86d0-4735-b11a-499081111af8",
    "Волгоград" => "a52b7389-0cfe-46fb-ae15-298652a64cf8",
    "Екатеринбург" => "2763c110-cb8b-416a-9dac-ad28a55b4402",
    "Ижевск" => "deb1d05a-71ce-40d1-b726-6ba85d70d58f",
    "Иркутск" => "8eeed222-72e7-47c3-ab3a-9a553c31cf72",

    "Казань" => "93b3df57-4c89-44df-ac42-96f05e9cd3b9",
    "Краснодар" => "7dfa745e-aa19-4688-b121-b655c11e482f",
    "Махачкала" => "727cdf1e-1b70-4e07-8995-9bf7ca9abefb",
    "Набережные Челны" => "748d7afa-7407-4876-9f40-764ecdd09bbd",
    "Новосибирск" => "8dea00e3-9aab-4d8e-887c-ef2aaa546456",
    "Новокузнецк" => "b28b6f6f-1435-444e-95a6-68c499b0d27a",
    "Пермь" => "a309e4ce-2f36-4106-b1ca-53e0f48a6d95",
    "Пенза" => "ff3292b1-a1d2-47d4-b35b-ac06b50555cc",
    "Ростов-на-Дону" => "c1cfe4b9-f7c2-423c-abfa-6ed1c05a15c5",
    "Рязань" => "86e5bae4-ef58-4031-b34f-5e9ff914cd55",

    "Самара" => "bb035cc3-1dc2-4627-9d25-a1bf2d4b936b",
    "Саратов" => "bf465fda-7834-47d5-986b-ccdb584a85a6",
    "Тольятти" => "242e87c1-584d-4360-8c4c-aae2fe90048e",
    "Томск" => "e3b0eae8-a4ce-4779-ae04-5c0797de66be",
    "Тюмень" => "9ae64229-9f7b-4149-b27a-d1f6ec74b5ce",
    "Ульяновск" => "bebfd75d-a0da-4bf9-8307-2e2c85eac463",
    "Уфа" => "7339e834-2cb4-4734-a4c7-1fca2c66e562",
    "Челябинск" => "a376e68d-724a-4472-be7c-891bdb09ae32",
    "Хабаровск" => "a4859da8-9977-4b62-8436-4e1b98c5d13f",
    "Ярославль" => "6b1bab7d-ee45-4168-a2a6-4ce2880d90d3",
]);

define("DEFAULT_CITY", [
    "ID" => "0c5b2444-70a0-4932-980c-b4dc0d3f02b5",
    "NAME" => "Москва",
]);

define("DADATA_API_ROOT", "https://suggestions.dadata.ru/suggestions/api/4_1/rs/");
define("DADATA_API_TOKEN", "8d1c08255520ec802c2e54f56727d1da384aa7bd");

define("YANDEX_MAPS_API_KEY", "099ac6cb-c67e-4211-9e78-51151f19a6d9");

define("MARKETPLACE_LIST_REVIEW_TEXT_LIMIT", 280);
define("RELATED_ARTICLE_PREVIEW_TEXT_LIMIT", 85);

define("ARTICLE_TYPE_PROPOSED_CODE", "proposed");

define("MAX_NOTIFICATIONS_SHOW", 5);

define("RATING_CALCULATE", [
    "READY_ORDER" => "1",
    "NEW_REVIEW" => "1",
    "READY_EVENT" => "0.5",
    "READY_EDUCATION" => "0.8",
    "READY_ARTICLE" => "0.2",
    "ARTICLE_LIKE" => "0.1",
    "BUY_GOOD_FROM_RAEK" => "3",
    "ANSWER_TIME" => "1",
    "ACTUAL_STATUS" => "1",
    "CANCEL_ORDER_WITHOUT_REASON" => "-5",
]);

define("TIME_IN_QUEUE" , 10);

define("LIST_EDUCATION_TYPE",["Бакалавр","Специалист","Магистр"]);
define("LIST_STATUS_CONTRACTOR",["new","inwork","completed","canceled","skipped","stay_review"]);

define("PHONE","8 800 100-84-15");
define("PHONE_TEL","88001008415");

define("CONNECTION_TYPE_CURL", 1);
define("CONNECTION_TYPE_SOAP", 2);