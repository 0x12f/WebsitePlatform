<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class V20240414153158 extends AbstractMigration
{
    public function change(): void
    {
        // create table params
        $table = $this->table('params', ['id' => false, 'primary_key' => 'name']);
        $table
            ->addColumn('name', 'string', ['limit' => 255, 'default' => ''])
            ->addColumn('value', 'text', ['default' => ''])
            ->create();

        // create table user group
        $table = $this->table('user_group', ['id' => false, 'primary_key' => 'uuid']);
        $table
            ->addColumn('uuid', 'char', ['limit' => 36])
            ->addColumn('title', 'string', ['limit' => 255, 'default' => ''])
            ->addColumn('description', 'text', ['default' => ''])
            ->addColumn('access', 'text', ['default' => '{}'])
            ->create();

        // create table user
        $table = $this->table('user', ['id' => false, 'primary_key' => 'uuid']);
        $table
            ->addColumn('uuid', 'char', ['limit' => 36])
            ->addColumn('group_uuid', 'char', ['limit' => 36, 'null' => true])
            ->addForeignKey('group_uuid', 'user_group', 'uuid', ['delete' => 'CASCADE'])
            ->addColumn('username', 'string', ['limit' => 64, 'default' => ''])
            ->addColumn('email', 'string', ['limit' => 120, 'default' => ''])
            ->addColumn('phone', 'string', ['limit' => 25, 'default' => ''])
            ->addColumn('password', 'string', ['limit' => 140, 'default' => ''])
            ->addColumn('firstname', 'string', ['limit' => 64, 'default' => ''])
            ->addColumn('lastname', 'string', ['limit' => 64, 'default' => ''])
            ->addColumn('patronymic', 'string', ['limit' => 64, 'default' => ''])
            ->addColumn('birthdate', 'date', ['default' => null, 'null' => true])
            ->addColumn('gender', 'string', ['limit' => 64, 'default' => ''])
            ->addColumn('country', 'string', ['limit' => 128, 'default' => ''])
            ->addColumn('city', 'string', ['limit' => 128, 'default' => ''])
            ->addColumn('address', 'string', ['limit' => 512, 'default' => ''])
            ->addColumn('postcode', 'string', ['limit' => 32, 'default' => ''])
            ->addColumn('additional', 'string', ['limit' => 1000, 'default' => ''])
            ->addColumn('allow_mail', 'boolean', ['default' => true])
            ->addColumn('company', 'text', ['default' => '{}'])
            ->addColumn('legal', 'text', ['default' => '{}'])
            ->addColumn('messenger', 'text', ['default' => '{}'])
            ->addColumn('status', 'string', ['limit' => 100, 'default' => 'work'])
            ->addColumn('register', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('change', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('website', 'string', ['limit' => 128, 'default' => ''])
            ->addColumn('source', 'string', ['limit' => 512, 'default' => ''])
            ->addColumn('auth_code', 'string', ['limit' => 12, 'default' => '', 'null' => false])
            ->addColumn('language', 'string', ['limit' => 5, 'default' => '', 'null' => false])
            ->addColumn('external_id', 'string', ['limit' => 255, 'default' => '', 'null' => false])
            ->addColumn('token', 'text', ['default' => '[]', 'null' => false])
            ->create();

        // create table user token
        $table = $this->table('user_token', ['id' => false, 'primary_key' => 'uuid']);
        $table
            ->addColumn('uuid', 'char', ['limit' => 36])
            ->addColumn('user_uuid', 'char', ['limit' => 36, 'null' => false])
            ->addForeignKey('user_uuid', 'user', 'uuid', ['delete' => 'CASCADE'])
            ->addColumn('unique', 'text', ['default' => '', 'null' => false])
            ->addColumn('comment', 'text', ['default' => '', 'null' => false])
            ->addColumn('ip', 'string', ['limit' => 16, 'default' => '', 'null' => false])
            ->addColumn('agent', 'string', ['limit' => 255, 'default' => '', 'null' => false])
            ->addColumn('date', 'datetime', ['default' => 'CURRENT_TIMESTAMP', 'null' => false])
            ->addIndex('user_uuid')
            ->create();

        // create table user integration
        $table = $this->table('user_integration', ['id' => false, 'primary_key' => 'uuid']);
        $table
            ->addColumn('uuid', 'char', ['limit' => 36])
            ->addColumn('user_uuid', 'char', ['limit' => 36, 'null' => false])
            ->addForeignKey('user_uuid', 'user', 'uuid', ['delete' => 'CASCADE'])
            ->addColumn('provider', 'text', ['default' => '', 'null' => false])
            ->addColumn('unique', 'string', ['limit' => 128, 'default' => '', 'null' => false])
            ->addColumn('date', 'datetime', ['default' => 'CURRENT_TIMESTAMP', 'null' => false])
            ->addIndex('user_uuid')
            ->addIndex(['user_uuid', 'provider', 'unique'], ['unique' => true])
            ->create();

        // create table user subscriber
        $table = $this->table('user_subscriber', ['id' => false, 'primary_key' => 'uuid']);
        $table
            ->addColumn('uuid', 'char', ['limit' => 36])
            ->addColumn('email', 'string', ['limit' => 120, 'default' => '', 'null' => false])
            ->addColumn('date', 'datetime', ['default' => 'CURRENT_TIMESTAMP', 'null' => false])
            ->addIndex(['email'], ['unique' => true])
            ->create();

        // create table page
        $table = $this->table('page', ['id' => false, 'primary_key' => 'uuid']);
        $table
            ->addColumn('uuid', 'char', ['limit' => 36])
            ->addColumn('title', 'string', ['limit' => 255, 'default' => ''])
            ->addColumn('address', 'string', ['limit' => 1000, 'default' => ''])
            ->addColumn('content', 'text', ['default' => ''])
            ->addColumn('type', 'string', ['limit' => 100])
            ->addColumn('template', 'string', ['limit' => 255, 'default' => ''])
            ->addColumn('meta', 'text', ['default' => '{}'])
            ->addColumn('date', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
            ->addIndex('title')
            ->addIndex('address')
            ->create();

        // create table form
        $table = $this->table('form', ['id' => false, 'primary_key' => 'uuid']);
        $table
            ->addColumn('uuid', 'char', ['limit' => 36])
            ->addColumn('title', 'string', ['limit' => 255, 'default' => ''])
            ->addColumn('address', 'string', ['limit' => 1000, 'default' => ''])
            ->addColumn('template', 'text', ['default' => ''])
            ->addColumn('templateFile', 'string', ['limit' => 255, 'default' => ''])
            ->addColumn('recaptcha', 'boolean', ['default' => true])
            ->addColumn('authorSend', 'boolean', ['default' => false])
            ->addColumn('origin', 'text', ['default' => '{}'])
            ->addColumn('mailto', 'text', ['default' => '{}'])
            ->addColumn('duplicate', 'string', ['limit' => 255, 'default' => ''])
            ->addIndex(['address'], ['unique' => true])
            ->create();

        // create table form data
        $table = $this->table('form_data', ['id' => false, 'primary_key' => 'uuid']);
        $table
            ->addColumn('uuid', 'char', ['limit' => 36])
            ->addColumn('form_uuid', 'char', ['limit' => 36, 'default' => '00000000-0000-0000-0000-000000000000'])
            ->addForeignKey('form_uuid', 'form', 'uuid', ['delete' => 'CASCADE'])
            ->addColumn('data', 'text', ['default' => '{}'])
            ->addColumn('message', 'text', ['default' => ''])
            ->addColumn('date', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
            ->addIndex(['form_uuid'])
            ->create();

        // create table publication category
        $table = $this->table('publication_category', ['id' => false, 'primary_key' => 'uuid']);
        $table
            ->addColumn('uuid', 'char', ['limit' => 36])
            ->addColumn('title', 'string', ['limit' => 255, 'default' => ''])
            ->addColumn('address', 'string', ['limit' => 1000, 'default' => ''])
            ->addColumn('parent_uuid', 'char', ['limit' => 36, 'null' => true])
            ->addForeignKey('parent_uuid', 'publication_category', 'uuid', ['delete' => 'CASCADE'])
            ->addColumn('description', 'text', ['default' => ''])
            ->addColumn('pagination', 'integer', ['default' => 10])
            ->addColumn('children', 'boolean', ['default' => true])
            ->addColumn('public', 'boolean', ['default' => true])
            ->addColumn('sort', 'text', ['default' => '{}'])
            ->addColumn('template', 'text', ['default' => '{}'])
            ->addColumn('meta', 'text', ['default' => '{}'])
            ->addIndex('parent_uuid')
            ->addIndex(['address'], ['unique' => true])
            ->create();

        // create table publication
        $table = $this->table('publication', ['id' => false, 'primary_key' => 'uuid']);
        $table
            ->addColumn('uuid', 'char', ['limit' => 36])
            ->addColumn('title', 'string', ['limit' => 255, 'default' => ''])
            ->addColumn('address', 'string', ['limit' => 1000, 'default' => ''])
            ->addColumn('category_uuid', 'char', ['limit' => 36])
            ->addForeignKey('category_uuid', 'publication_category', 'uuid', ['delete' => 'CASCADE'])
            ->addColumn('user_uuid', 'char', ['limit' => 36, 'null' => true])
            ->addForeignKey('user_uuid', 'user', 'uuid', ['delete' => 'CASCADE'])
            ->addColumn('content', 'text', ['default' => '{}'])
            ->addColumn('meta', 'text', ['default' => '{}'])
            ->addColumn('date', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('external_id', 'string', ['limit' => 255, 'default' => ''])
            ->addIndex('category_uuid')
            ->addIndex('user_uuid')
            ->addIndex(['address'], ['unique' => true])
            ->create();

        // create table catalog category
        $table = $this->table('catalog_category', ['id' => false, 'primary_key' => 'uuid']);
        $table
            ->addColumn('uuid', 'char', ['limit' => 36])
            ->addColumn('title', 'string', ['limit' => 255, 'default' => ''])
            ->addColumn('description', 'text', ['default' => ''])
            ->addColumn('address', 'string', ['limit' => 1000, 'default' => ''])
            ->addColumn('parent_uuid', 'char', ['limit' => 36, 'default' => null, 'null' => true])
            ->addForeignKey('parent_uuid', 'catalog_category', 'uuid', ['delete' => 'CASCADE'])
            ->addColumn('pagination', 'integer', ['default' => 10])
            ->addColumn('children', 'boolean', ['default' => false])
            ->addColumn('hidden', 'boolean', ['default' => false])
            ->addColumn('order', 'integer', ['default' => 1])
            ->addColumn('status', 'string', ['limit' => 100, 'default' => 'work'])
            ->addColumn('sort', 'text', ['default' => '{}'])
            ->addColumn('meta', 'text', ['default' => '{}'])
            ->addColumn('template', 'text', ['default' => '{}'])
            ->addColumn('external_id', 'string', ['limit' => 255, 'default' => ''])
            ->addColumn('export', 'string', ['limit' => 64, 'default' => 'manual'])
            ->addColumn('system', 'string', ['limit' => 512, 'default' => ''])
            ->addIndex(['address'])
            ->addIndex(['order'])
            ->addIndex(['parent_uuid'])
            ->addIndex(['parent_uuid', 'address', 'external_id'], ['unique' => true])
            ->create();

        // create table catalog product
        $table = $this->table('catalog_product', ['id' => false, 'primary_key' => 'uuid']);
        $table
            ->addColumn('uuid', 'char', ['limit' => 36])
            ->addColumn('title', 'string', ['limit' => 255, 'default' => ''])
            ->addColumn('type', 'string', ['limit' => 100, 'default' => 'product'])
            ->addColumn('description', 'text', ['default' => ''])
            ->addColumn('extra', 'text', ['default' => ''])
            ->addColumn('address', 'string', ['limit' => 1000, 'default' => ''])
            ->addColumn('category_uuid', 'char', ['limit' => 36])
            ->addForeignKey('category_uuid', 'catalog_category', 'uuid', ['delete' => 'CASCADE'])
            ->addColumn('vendorcode', 'text', ['default' => ''])
            ->addColumn('barcode', 'text', ['default' => ''])
            ->addColumn('tax', 'decimal', ['precision' => 10, 'scale' => 2, 'default' => '0'])
            ->addColumn('priceFirst', 'decimal', ['precision' => 10, 'scale' => 2, 'default' => '0'])
            ->addColumn('price', 'decimal', ['precision' => 10, 'scale' => 2, 'default' => '0'])
            ->addColumn('priceWholesale', 'decimal', ['precision' => 10, 'scale' => 2, 'default' => '0'])
            ->addColumn('priceWholesaleFrom', 'decimal', ['precision' => 10, 'scale' => 2, 'default' => '0'])
            ->addColumn('discount', 'decimal', ['precision' => 10, 'scale' => 2, 'default' => '0'])
            ->addColumn('special', 'boolean', ['default' => '0'])
            ->addColumn('dimension', 'text', ['default' => '{}'])
            ->addColumn('quantity', 'decimal', ['precision' => 10, 'scale' => 2, 'default' => '1'])
            ->addColumn('quantityMin', 'decimal', ['precision' => 10, 'scale' => 2, 'default' => '1'])
            ->addColumn('stock', 'decimal', ['precision' => 10, 'scale' => 2, 'default' => '0'])
            ->addColumn('country', 'string', ['limit' => 255, 'default' => ''])
            ->addColumn('manufacturer', 'string', ['limit' => 255, 'default' => ''])
            ->addColumn('tags', 'text', ['default' => '{}'])
            ->addColumn('order', 'integer', ['default' => '1'])
            ->addColumn('status', 'string', ['limit' => 100, 'default' => 'work'])
            ->addColumn('date', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('meta', 'text', ['default' => '{}'])
            ->addColumn('external_id', 'string', ['limit' => 255, 'default' => ''])
            ->addColumn('export', 'string', ['limit' => 64, 'default' => 'manual'])
            ->addIndex(['address'])
            ->addIndex(['category_uuid'])
            ->addIndex(['country'])
            ->addIndex(['manufacturer'])
            ->addIndex(['order'])
            ->addIndex(['price', 'priceFirst', 'priceWholesale'])
            ->addIndex(['category_uuid', 'address', 'dimension', 'external_id'], ['unique' => true])
            ->create();

        // create table catalog product related
        $table = $this->table('catalog_product_related', ['id' => false, 'primary_key' => ['product_uuid', 'related_uuid']]);
        $table
            ->addColumn('product_uuid', 'char', ['limit' => 36])
            ->addColumn('related_uuid', 'char', ['limit' => 36])
            ->addColumn('count', 'double', ['default' => 1])
            ->addForeignKey('product_uuid', 'catalog_product', 'uuid', ['delete' => 'CASCADE', 'update' => 'CASCADE'])
            ->addForeignKey('related_uuid', 'catalog_product', 'uuid', ['delete' => 'CASCADE', 'update' => 'CASCADE'])
            ->addIndex('product_uuid')
            ->addIndex('related_uuid')
            ->create();

        // create table catalog attribute
        $table = $this->table('catalog_attribute', ['id' => false, 'primary_key' => 'uuid']);
        $table
            ->addColumn('uuid', 'char', ['limit' => 36, 'null' => false])
            ->addColumn('title', 'string', ['limit' => 255, 'default' => '', 'null' => false])
            ->addColumn('address', 'string', ['limit' => 255, 'default' => '', 'null' => false])
            ->addColumn('type', 'string', ['limit' => 100, 'default' => 'string', 'null' => false])
            ->addColumn('group', 'string', ['limit' => 255, 'default' => 'string', 'null' => false])
            ->addColumn('is_filter', 'boolean', ['default' => true])
            ->addIndex(['address'], ['unique' => true])
            ->addIndex(['group'])
            ->create();

        // create table catalog attribute category
        $table = $this->table('catalog_attribute_category', ['id' => false, 'primary_key' => ['category_uuid', 'attribute_uuid']]);
        $table
            ->addColumn('category_uuid', 'char', ['limit' => 36, 'null' => false])
            ->addColumn('attribute_uuid', 'char', ['limit' => 36, 'null' => false])
            ->addIndex('category_uuid')
            ->addIndex('attribute_uuid')
            ->addForeignKey('category_uuid', 'catalog_category', 'uuid', ['delete' => 'CASCADE', 'update' => 'CASCADE'])
            ->addForeignKey('attribute_uuid', 'catalog_attribute', 'uuid', ['delete' => 'CASCADE', 'update' => 'CASCADE'])
            ->create();

        // create table catalog attribute product
        $table = $this->table('catalog_attribute_product', ['id' => false, 'primary_key' => ['product_uuid', 'attribute_uuid']]);
        $table
            ->addColumn('product_uuid', 'char', ['limit' => 36, 'null' => false])
            ->addColumn('attribute_uuid', 'char', ['limit' => 36, 'null' => false])
            ->addColumn('value', 'string', ['limit' => 1000, 'default' => '', 'null' => false])
            ->addIndex('product_uuid')
            ->addIndex('attribute_uuid')
            ->addForeignKey('product_uuid', 'catalog_product', 'uuid', ['delete' => 'CASCADE', 'update' => 'CASCADE'])
            ->addForeignKey('attribute_uuid', 'catalog_attribute', 'uuid', ['delete' => 'CASCADE', 'update' => 'CASCADE'])
            ->create();


        // create table guestbook
        $table = $this->table('guestbook', ['id' => false, 'primary_key' => 'uuid']);
        $table
            ->addColumn('uuid', 'char', ['limit' => 36])
            ->addColumn('name', 'string', ['limit' => 255, 'default' => ''])
            ->addColumn('email', 'string', ['limit' => 120, 'default' => ''])
            ->addColumn('message', 'text', ['default' => ''])
            ->addColumn('response', 'text', ['default' => ''])
            ->addColumn('status', 'string', ['limit' => 100, 'default' => 'work'])
            ->addColumn('date', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
            ->create();

        // create table file
        $table = $this->table('file', ['id' => false, 'primary_key' => 'uuid']);
        $table
            ->addColumn('uuid', 'char', ['limit' => 36])
            ->addColumn('name', 'string', ['limit' => 255, 'default' => ''])
            ->addColumn('ext', 'string', ['limit' => 255, 'default' => ''])
            ->addColumn('type', 'string', ['limit' => 255, 'default' => ''])
            ->addColumn('size', 'integer', ['default' => 0])
            ->addColumn('salt', 'string', ['limit' => 255, 'default' => ''])
            ->addColumn('hash', 'string', ['limit' => 255, 'default' => ''])
            ->addColumn('private', 'boolean', ['default' => 0])
            ->addColumn('date', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
            ->create();

        // create table file related
        $table = $this->table('file_related');
        $table
            ->addColumn('file_uuid', 'char', ['limit' => 36])
            ->addColumn('entity_uuid', 'char', ['limit' => 36])
            ->addColumn('order', 'integer', ['default' => 1])
            ->addColumn('comment', 'text', ['default' => ''])
            ->addColumn('object_type', 'string', ['limit' => 255])
            ->addIndex('file_uuid')
            ->addIndex('entity_uuid')
            ->create();

        // create table reference
        $table = $this->table('reference', ['id' => false, 'primary_key' => 'uuid']);
        $table
            ->addColumn('uuid', 'char', ['limit' => 36])
            ->addColumn('type', 'string', ['limit' => 100, 'default' => 'text'])
            ->addColumn('title', 'string', ['limit' => 255, 'default' => ''])
            ->addColumn('value', 'text', ['default' => '{}'])
            ->addColumn('order', 'integer', ['default' => 1])
            ->addColumn('status', 'boolean', ['default' => 0])
            ->addIndex(['type', 'title'], ['unique' => true])
            ->create();

        // create table task
        $table = $this->table('task', ['id' => false, 'primary_key' => 'uuid']);
        $table
            ->addColumn('uuid', 'char', ['limit' => 36])
            ->addColumn('title', 'string', ['limit' => 255, 'default' => ''])
            ->addColumn('action', 'string', ['limit' => 255, 'default' => ''])
            ->addColumn('progress', 'decimal', ['precision' => 10, 'scale' => 2, 'default' => '0'])
            ->addColumn('status', 'string', ['limit' => 100, 'default' => 'queue'])
            ->addColumn('params', 'text', ['default' => '{}'])
            ->addColumn('output', 'string', ['limit' => 1000, 'default' => ''])
            ->addColumn('date', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
            ->addIndex('title')
            ->addIndex('status')
            ->create();
    }
}
