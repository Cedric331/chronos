
create table `jour_feries` (`id` bigint unsigned not null auto_increment primary key, `date` date not null, `name` varchar(255) null, `created_at` timestamp null, `updated_at` timestamp null) default character set utf8mb4 collate 'utf8mb4_unicode_ci' engine = innoDB;
alter table `jour_feries` add unique `jour_feries_date_unique`(`date`);
create table `jour_ferie_travailles` (`id` bigint unsigned not null auto_increment primary key, `collaborateurs` json null, `jour_ferie_id` bigint unsigned not null, `hub_id` bigint unsigned not null, `created_at` timestamp null, `updated_at` timestamp null) default character set utf8mb4 collate 'utf8mb4_unicode_ci' engine = innoDB;
alter table `jour_ferie_travailles` add unique `jour_ferie_travailles_jour_ferie_id_hub_id_unique`(`jour_ferie_id`, `hub_id`);
