
create table `jours_feries` (`id` bigint unsigned not null auto_increment primary key, `name` varchar(255) null, `annee` year not null, `date` date not null, `hub_id` bigint unsigned not null, `created_at` timestamp null, `updated_at` timestamp null) default character set utf8mb4 collate 'utf8mb4_unicode_ci' engine = innoDB;
alter table `jours_feries` add constraint `jours_feries_hub_id_foreign` foreign key (`hub_id`) references `hubs` (`id`);
alter table `jours_feries` add unique `jours_feries_annee_date_hub_id_unique`(`annee`, `date`, `hub_id`);
create table `collaborateur_jours_feries` (`id` bigint unsigned not null auto_increment primary key, `collaborateur_id` bigint unsigned not null, `jours_ferie_id` bigint unsigned not null, `created_at` timestamp null, `updated_at` timestamp null) default character set utf8mb4 collate 'utf8mb4_unicode_ci' engine = innoDB;
alter table `collaborateur_jours_feries` add constraint `collaborateur_jours_feries_collaborateur_id_foreign` foreign key (`collaborateur_id`) references `collaborateurs` (`id`);
alter table `collaborateur_jours_feries` add constraint `collaborateur_jours_feries_jours_ferie_id_foreign` foreign key (`jours_ferie_id`) references `jours_feries` (`id`);
