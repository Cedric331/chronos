
 create table `type_rotations` (`id` bigint unsigned not null auto_increment primary key, `type` varchar(3) not null, `hours` varchar(5) null, `hub_id` bigint unsigned not null, `created_at` timestamp null, `updated_at` timestamp null) default character set utf8mb4 collate 'utf8mb4_unicode_ci' engine = innoDB;
 alter table `type_rotations` add constraint `type_rotations_hub_id_foreign` foreign key (`hub_id`) references `hubs` (`id`);
 alter table `type_rotations` add unique `type_rotations_type_hub_id_unique`(`type`, `hub_id`);
 create table `rotations` (`id` bigint unsigned not null auto_increment primary key, `day` varchar(255) not null, `horaire` json not null, `type_rotation_id` bigint unsigned not null, `created_at` timestamp null, `updated_at` timestamp null) default character set utf8mb4 collate 'utf8mb4_unicode_ci' engine = innoDB;
