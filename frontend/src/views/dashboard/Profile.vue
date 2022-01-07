<template>
    <dashboard-layout>
        <v-row v-if="user && user.patient">
            <v-col cols="12">
                <v-card class="mx-auto my-auto" rounded="lg">
                    <v-card-text>
                        <v-row>
                            <v-col cols="auto">
                                <v-row>
                                    <v-col
                                        cols="12"
                                        class="d-flex justify-center"
                                    >
                                        <v-avatar size="150">
                                            <img
                                                :src="user.avatar_url"
                                                alt="avatar"
                                            />
                                        </v-avatar>
                                    </v-col>
                                    <v-col class="avatar">
                                        <CustomFileUpload
                                            label="Загрузить фото"
                                            v-model="user.avatar_url"
                                            @change="handleUpdateProfile"
                                        />
                                    </v-col>
                                </v-row>
                            </v-col>
                            <v-col>
                                <v-form>
                                    <v-row>
                                        <v-col cols="12">
                                            <CustomInput
                                                v-model="user.name"
                                                label="Ваше имя"
                                            />
                                        </v-col>
                                        <v-col cols="12">
                                            <CustomInput
                                                v-model="user.email"
                                                label="Ваше email"
                                            />
                                        </v-col>
                                        <v-col cols="12">
                                            <v-slider
                                                v-model="user.patient.goal"
                                                label="Цель"
                                                max="24"
                                                min="0"
                                                thumb-label="always"
                                                color="secondary"
                                            ></v-slider>
                                        </v-col>
                                    </v-row>
                                </v-form>
                            </v-col>
                        </v-row>
                    </v-card-text>
                </v-card>
            </v-col>
            <v-col>
                <v-card rounded="lg">
                    <v-card-title> Сменить пароль</v-card-title>
                    <v-card-text>
                        <v-row dense>
                            <v-col cols="12"
                                ><CustomInput
                                    :error="errors.old_password"
                                    type="password"
                                    v-model="old_password"
                                    label="Текущий пароль"
                            /></v-col>
                            <v-col cols="12"
                                ><CustomInput
                                    :error="errors.new_password"
                                    type="password"
                                    v-model="new_password"
                                    label="Новый пароль"
                            /></v-col>
                            <v-col cols="12"
                                ><CustomInput
                                    :error="errors.password_confirmation"
                                    type="password"
                                    v-model="password_confirmation"
                                    label="Повторите пароль"
                            /></v-col>
                            <CustomButton @click="handleChangePassword" style="width: 100%"
                                >Сохранить</CustomButton
                            >
                        </v-row>
                    </v-card-text>
                </v-card>
            </v-col>
            <v-col>
                <v-card rounded="lg">
                    <v-card-title> Подписка</v-card-title>
                    <v-card-text>
                        <v-row dense>
                            <v-col cols="12"></v-col>
                            <v-col cols="12">
                                <div>
                                    <span class="text-body-1"
                                        ><b>Basic </b> до 14.10.2021</span
                                    >
                                </div>
                                <div>Следующее списание 13.10.2021</div>
                            </v-col>
                            <v-col cols="12" class="d-flex justify-center"
                                >Подробнее о подписке</v-col
                            >
                            <CustomButton style="width: 100%"
                                >Расширить подписку</CustomButton
                            >
                        </v-row>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
    </dashboard-layout>
</template>

<script>
import {mapMutations} from "vuex";
import DashboardLayout from "../../layouts/dashboard/Index";
import Api from "../../api/Api";
import User from "../../models/User";
export default {
    name: "Profile",
    components: { DashboardLayout },
    data: function () {
        return {
            user: {},
            old_password: "",
            new_password: "",
            password_confirmation: "",
            errors: {},
        };
    },
    methods: {
        async handleUpdateProfile() {
            const user = new User(this.user);
            this.user = await user.save();
        },
        async handleChangePassword() {
            const { old_password, new_password, password_confirmation } = this;
            await Api.updatePassword({old_password, new_password, password_confirmation, userId: this.$auth.user().id})
                .then(response => {
                    this.errors = {};
                    if (response.data.success) {
                        this.old_password = '';
                        this.new_password = '';
                        this.password_confirmation = '';
                        this.showSnackBar({color: 'success', timeout: 3000, text: 'Password was changed successfully'})
                    }
                    else {
                        this.errors.old_password = 'Old Password is mismatched';
                    }
                })
                .catch(e => this.errors = e.response.data.errors)
        },
        ...mapMutations(['showSnackBar']),
    },
    mounted() {
        this.user = this.$auth.user();
    },
};
</script>

<style scoped>
.avatar {
    height: 85px;
}
</style>
