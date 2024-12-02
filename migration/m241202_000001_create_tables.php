<?php

use yii\db\Migration;

/**
 * Class m241202_000001_create_tables
 */
class m241202_000001_create_tables extends Migration
{
    public function safeUp()
    {
        // Создание таблицы Users
        $this->createTable('users', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'login' => $this->string()->notNull()->unique(),
            'avatar' => $this->string(),
            'email' => $this->string()->notNull()->unique(),
            'phone' => $this->string(),
            'website' => $this->string(),
        ]);

        // Создание таблицы Companies
        $this->createTable('companies', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'website' => $this->string(),
            'address' => $this->string(),
        ]);

        // Создание таблицы Blogs
        $this->createTable('blogs', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->null(),
            'company_id' => $this->integer()->null(),
            'foreign key (user_id) references users(id) on delete set null',
            'foreign key (company_id) references companies(id) on delete set null',
        ]);

        // Создание таблицы Materials
        $this->createTable('materials', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'content' => $this->text()->notNull(),
            'blog_id' => $this->integer()->null(),
            'foreign key (blog_id) references blogs(id) on delete set null',
        ]);

        // Создание таблицы Subscriptions
        $this->createTable('subscriptions', [
            'user_id' => $this->integer()->notNull(),
            'blog_id' => $this->integer()->notNull(),
            'primary key (user_id, blog_id)',
            'foreign key (user_id) references users(id) on delete cascade',
            'foreign key (blog_id) references blogs(id) on delete cascade',
        ]);

        // Создание таблицы Comments
        $this->createTable('comments', [
            'material_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'content' => $this->text()->notNull(),
            'primary key (material_id, user_id)',
            'foreign key (material_id) references materials(id) on delete cascade',
            'foreign key (user_id) references users(id) on delete cascade',
        ]);
		
		// Индексы для таблицы users
        $this->createIndex('idx-users-username', 'users', 'name');

        // Индексы для таблицы comments
        $this->createIndex('idx-comments-user_id', 'comments', 'user_id');
        $this->createIndex('idx-comments-material_id', 'comments', 'material_id');

        // Индексы для таблицы subscriptions
        $this->createIndex('idx-subscriptions-user_id', 'subscriptions', 'user_id');
        $this->createIndex('idx-subscriptions-blog_id', 'subscriptions', 'blog_id');

        // Добавление демо данных
        $this->batchInsert('users', ['name', 'login', 'email'], [
            ['User 1', 'user1', 'user1@example.com'],
            ['User 2', 'user2', 'user2@example.com'],
        ]);

        $this->batchInsert('companies', ['title', 'website'], [
            ['Company1', 'https://company1.com'],
            ['Company2', 'https://company2.com'],
        ]);

        $this->batchInsert('blogs', ['user_id', 'company_id'], [
            [1, null],
            [null, 1],
        ]);

        $this->batchInsert('materials', ['title', 'content', 'blog_id'], [
            ['Article1', 'Content of article 1', 1],
            ['Article2', 'Content of article 2', null],
        ]);

        $this->batchInsert('subscriptions', ['user_id', 'blog_id'], [
            [1, 1],
            [2, 1],
        ]);

        $this->batchInsert('comments', ['material_id', 'user_id', 'content'], [
            [1, 1, 'Comment on article 1 by user 1'],
            [2, 2, 'Comment on article 2 by user 2'],
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('comments');
        $this->dropTable('subscriptions');
        $this->dropTable('materials');
        $this->dropTable('blogs');
        $this->dropTable('companies');
        $this->dropTable('users');
    }
}