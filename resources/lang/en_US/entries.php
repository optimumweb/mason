<?php

return [
    'title' => "Entries",
    'singular' => "Entry",
    'plural' => "Entries",
    'pagination' => "{0} No entries|{1} One entry|[2,*] Showing :count entries out of :total",
    'noRecords' => "There are no :entryType at this moment.",

    'attributes' => [
        'id' => "ID",
        'name' => "Name",
        'locale' => "Language",
        'original' => "Original",
        'translations' => "Translations",
        'title' => "Title",
        'content' => "Content",
        'summary' => "Sommaire",
        'author' => "Author",
        'cover' => "Cover image",
        'is_home' => "Homepage",
        'published_at' => "Published on",
        'status' => "Status",
        'created_at' => "Created on",
        'updated_at' => "Updated on",
        'deleted_at' => "Deleted on",
    ],

    'meta' => [
        'title' => "Titre",
        'description' => "Description",
        'keywords' => "Keywords"
    ],

    'statuses' => [
        'draft' => "Draft",
        'published' => "Published",
        'scheduled' => "Scheduled",
    ],

    'actions' => [
        'create' => [
            'label' => "New :entryType",
        ],

        'view' => [
            'label' => "View",
        ],

        'edit' => [
            'label' => "Edit",
        ],

        'editOriginal' => [
            'label' => "Edit original",
        ],

        'destroy' => [
            'label' => "Delete",
        ],

        'save' => [
            'label' => "Save",
        ],

        'publish' => [
            'label' => "Publish",
        ],

        'cancel' => [
            'label' => "Cancel",
        ],
    ],
];
